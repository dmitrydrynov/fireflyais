<?php

/**
 * UserRepository.php
 * Copyright (c) 2019 james@firefly-iii.org
 *
 * This file is part of Firefly III (https://github.com/firefly-iii).
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace FireflyIII\Repositories\User;

use Error;
use Exception;
use FireflyIII\Exceptions\FireflyException;
use FireflyIII\Models\BudgetLimit;
use FireflyIII\Models\Role;
use FireflyIII\Models\UserGroup;
use FireflyIII\User;
use FireflyIII\Models\Account;
use FireflyIII\Models\Attachment;
use FireflyIII\Models\Bill;
use FireflyIII\Models\Category;
use FireflyIII\Models\Rule;
use FireflyIII\Models\RuleGroup;
use FireflyIII\Models\Tag;
use FireflyIII\Models\Transaction;
use FireflyIII\Models\TransactionJournal;
use FireflyIII\Models\Budget;
use Illuminate\Support\Collection;
use Log;
use Str;

/**
 * Class UserRepository.
 *
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * This updates the users email address and records some things so it can be confirmed or undone later.
     * The user is blocked until the change is confirmed.
     *
     * @param User   $user
     * @param string $newEmail
     *
     * @return bool
     * @throws Exception
     * @see updateEmail
     *
     */
    public function changeEmail(User $user, string $newEmail): bool
    {
        $oldEmail = $user->email;

        // save old email as pref
        app('preferences')->setForUser($user, 'previous_email_latest', $oldEmail);
        app('preferences')->setForUser($user, 'previous_email_' . date('Y-m-d-H-i-s'), $oldEmail);

        // set undo and confirm token:
        app('preferences')->setForUser($user, 'email_change_undo_token', bin2hex(random_bytes(16)));
        app('preferences')->setForUser($user, 'email_change_confirm_token', bin2hex(random_bytes(16)));
        // update user

        $user->email        = $newEmail;
        $user->blocked      = true;
        $user->blocked_code = 'email_changed';
        $user->save();

        return true;
    }

    /**
     * @param User   $user
     * @param string $password
     *
     * @return bool
     */
    public function changePassword(User $user, string $password): bool
    {
        $user->password = bcrypt($password);
        $user->save();

        return true;
    }

    /**
     * @param User   $user
     * @param bool   $isBlocked
     * @param string $code
     *
     * @return bool
     */
    public function changeStatus(User $user, bool $isBlocked, string $code): bool
    {
        // change blocked status and code:
        $user->blocked      = $isBlocked;
        $user->blocked_code = $code;
        $user->save();

        return true;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->all()->count();
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return User::orderBy('id', 'DESC')->get(['users.*']);
    }

    /**
     * @param string $name
     * @param string $displayName
     * @param string $description
     *
     * @return Role
     */
    public function createRole(string $name, string $displayName, string $description): Role
    {
        return Role::create(['name' => $name, 'display_name' => $displayName, 'description' => $description]);
    }

    /**
     * @param User $user
     *
     * @return bool
     * @throws Exception
     */
    public function destroy(User $user): bool
    {
        Log::debug(sprintf('Calling delete() on user %d', $user->id));

        if ($user->hasRole('owner')) {
            $user->userGroup()->delete();
        }

        $user->groupMemberships()->delete();
        $user->delete();
        $this->deleteEmptyGroups();

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteEmptyGroups(): void
    {
        $groups = UserGroup::get();
        /** @var UserGroup $group */
        foreach ($groups as $group) {
            $count = $group->members()->count();
            if (0 === $count) {
                Log::info(sprintf('Deleted empty group #%d ("%s")', $group->id, $group->title));
                $group->delete();
            }
        }
    }

    /**
     * @param int $userId
     *
     * @return User|null
     */
    public function find(int $userId): ?User
    {
        return User::find($userId);
    }

    /**
     * @param string $email
     *
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Returns the first user in the DB. Generally only works when there is just one.
     *
     * @return null|User
     */
    public function first(): ?User
    {
        return User::orderBy('id', 'ASC')->first();
    }

    /**
     * @param User $user
     *
     * @return string|null
     */
    public function getRoleByUser(User $user): ?string
    {
        /** @var Role|null $role */
        $role = $user->roles()->first();
        if (null !== $role) {
            return $role->name;
        }

        return null;
    }

    /**
     * Return basic user information.
     *
     * @param User $user
     *
     * @return array
     */
    public function getUserData(User $user): array
    {
        $return = [];

        // two factor:
        $return['has_2fa']             = $user->mfa_secret !== null;
        $return['is_admin']            = $this->hasRole($user, 'owner');
        $return['blocked']             = 1 === (int) $user->blocked;
        $return['blocked_code']        = $user->blocked_code;
        $return['accounts']            = $user->userGroup->accounts()->count();
        $return['journals']            = $user->userGroup->transactionJournals()->count();
        $return['transactions']        = $user->userGroup->transactions()->count();
        $return['attachments']         = $user->userGroup->attachments()->count();
        $return['attachments_size']    = $user->userGroup->attachments()->sum('size');
        $return['bills']               = $user->userGroup->bills()->count();
        $return['categories']          = $user->userGroup->categories()->count();
        $return['budgets']             = $user->userGroup->budgets()->count();
        $return['budgets_with_limits'] = BudgetLimit::distinct()
            ->leftJoin('budgets', 'budgets.id', '=', 'budget_limits.budget_id')
            ->where('amount', '>', 0)
            ->whereNull('budgets.deleted_at')
            ->where('budgets.user_id', $user->id)
            ->count('budget_limits.budget_id');
        $return['rule_groups']         = $user->userGroup->ruleGroups()->count();
        $return['rules']               = $user->userGroup->rules()->count();
        $return['tags']                = $user->userGroup->tags()->count();

        return $return;
    }

    /**
     * @param User   $user
     * @param string $role
     *
     * @return bool
     */
    public function hasRole(User $user, string $role): bool
    {
        // setPermissionsTeamId($user->user_group_id);

        return $user->hasRole($role);
    }

    /**
     * Set MFA code.
     *
     * @param User        $user
     * @param string|null $code
     */
    public function setMFACode(User $user, ?string $code): void
    {
        $user->mfa_secret = $code;
        $user->save();
    }

    /**
     * @param array $data
     *
     * @return User
     */
    public function store(array $data): User
    {
        $user = User::create(
            [
                'blocked'      => $data['blocked'] ?? false,
                'blocked_code' => $data['blocked_code'] ?? null,
                'email'        => $data['email'],
                'password'     => Str::random(24),
                'user_group_id' => auth()->user()->user_group_id || null,
            ]
        );

        $roles = $data['role'] ? explode(',', $data['role']) : [];

        if (count($roles) > 0) foreach ($roles as $role) {
            $this->attachRole($user, $role);
        }

        return $user;
    }

    /**
     * @param User   $user
     * @param string $role
     *
     * @return bool
     */
    public function attachRole(User $user, mixed $role): bool
    {
        // setPermissionsTeamId($user->user_group_id);
        $user->assignRole($role);

        return true;
        // $roleObject = Role::where('name', $role)->first();
        // if (null === $roleObject) {
        //     Log::error(sprintf('Could not find role "%s" in attachRole()', $role));

        //     return false;
        // }

        // try {
        //     $user->roles()->attach($roleObject);
        // } catch (QueryException $e) {
        //     // don't care
        //     Log::error(sprintf('Query exception when giving user a role: %s', $e->getMessage()));
        // }

        // return true;
    }

    /**
     * @param User $user
     */
    public function unblockUser(User $user): void
    {
        $user->blocked      = false;
        $user->blocked_code = '';
        $user->save();
    }

    /**
     * Update user info.
     *
     * @param User  $user
     * @param array $data
     *
     * @return User
     * @throws FireflyException
     */
    public function update(User $user, array $data): User
    {
        $this->updateEmail($user, $data['email'] ?? '');
        if (array_key_exists('blocked', $data) && is_bool($data['blocked'])) {
            $user->blocked = $data['blocked'];
        }
        if (array_key_exists('blocked_code', $data) && '' !== $data['blocked_code'] && is_string($data['blocked_code'])) {
            $user->blocked_code = $data['blocked_code'];
        }
        if (array_key_exists('role', $data) && '' === $data['role']) {
            $user->removeRole('owner');
            $user->removeRole('demo');
        }

        $user->save();

        return $user;
    }

    /**
     * This updates the users email address. Same as changeEmail just without most logging. This makes sure that the undo/confirm routine can't catch this one.
     * The user is NOT blocked.
     *
     * @param User   $user
     * @param string $newEmail
     *
     * @return bool
     * @throws FireflyException
     * @see changeEmail
     */
    public function updateEmail(User $user, string $newEmail): bool
    {
        if ('' === $newEmail) {
            return true;
        }
        $oldEmail = $user->email;

        // save old email as pref
        app('preferences')->setForUser($user, 'admin_previous_email_latest', $oldEmail);
        app('preferences')->setForUser($user, 'admin_previous_email_' . date('Y-m-d-H-i-s'), $oldEmail);

        $user->email = $newEmail;
        $user->save();

        return true;
    }

    /**
     * Remove any role the user has.
     *
     * @param User   $user
     * @param string $role
     */
    public function removeRole(User $user, string $role): void
    {
        $user->removeRole($role);
    }

    /**
     * @param string $role
     *
     * @return Role|null
     */
    public function getRole(string $role): ?Role
    {
        return Role::where('name', $role)->first();
    }
}
