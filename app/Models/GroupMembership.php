<?php

/*
 * GroupMembership.php
 * Copyright (c) 2021 james@firefly-iii.org
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

namespace FireflyIII\Models;

use Eloquent;
use FireflyIII\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class GroupMembership
 *
 * @property int            $id
 * @property Carbon|null    $created_at
 * @property Carbon|null    $updated_at
 * @property string|null    $deleted_at
 * @property int            $user_id
 * @property int            $user_group_id
 * @property int            $user_role_id
 * @property-read User      $user
 * @property-read UserGroup $userGroup
 * @property-read UserRole  $userRole
 * @method static Builder|GroupMembership newModelQuery()
 * @method static Builder|GroupMembership newQuery()
 * @method static Builder|GroupMembership query()
 * @method static Builder|GroupMembership whereCreatedAt($value)
 * @method static Builder|GroupMembership whereDeletedAt($value)
 * @method static Builder|GroupMembership whereId($value)
 * @method static Builder|GroupMembership whereUpdatedAt($value)
 * @method static Builder|GroupMembership whereUserGroupId($value)
 * @method static Builder|GroupMembership whereUserId($value)
 * @method static Builder|GroupMembership whereUserRoleId($value)
 * @mixin Eloquent
 */
class GroupMembership extends Model
{
    protected $fillable = ['user_id', 'user_group_id', 'user_role_id'];

    /**
     * Route binder. Converts the key in the URL to the specified object (or throw 404).
     *
     * @param string $value
     *
     * @return GroupMembership
     * @throws NotFoundHttpException
     */
    public static function routeBinder(string $value): User
    {
        if (auth()->check()) {
            $groupMembershipId = (int) $value;
            $user = auth()->user();
            $groupMembership = $user->groupMemberships()->find($groupMembershipId);
            if (null !== $groupMembership) {
                return $groupMembership;
            }
        }
        throw new NotFoundHttpException;
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function userGroup(): BelongsTo
    {
        return $this->belongsTo(UserGroup::class);
    }

    /**
     * @return BelongsTo
     */
    public function userRole(): BelongsTo
    {
        return $this->belongsTo(UserRole::class);
    }
}
