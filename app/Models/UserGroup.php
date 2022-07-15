<?php

/*
 * UserGroup.php
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

use FireflyIII\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class UserGroup
 *
 * @property int                               $id
 * @property Carbon|null                       $created_at
 * @property Carbon|null                       $updated_at
 * @property string|null                       $deleted_at
 * @property string                            $title
 * @property-read int|null                     $group_memberships_count
 * @method static Builder|UserGroup newModelQuery()
 * @method static Builder|UserGroup newQuery()
 * @method static Builder|UserGroup query()
 * @method static Builder|UserGroup whereCreatedAt($value)
 * @method static Builder|UserGroup whereDeletedAt($value)
 * @method static Builder|UserGroup whereId($value)
 * @method static Builder|UserGroup whereTitle($value)
 * @method static Builder|UserGroup whereUpdatedAt($value)
 * @mixin Eloquent
 */
class UserGroup extends Model
{
    protected $fillable = ['title'];

    /**
     * @codeCoverageIgnore
     *
     * @return HasMany
     */
    public function members(): HasMany | Builder
    {
        if (auth()->user()->isSuperAdmin() && session()->get('active_user_group') === 'all') {
            return User::query();
        }

        return $this->hasMany(User::class);
    }

    public function accounts(): HasMany | Builder
    {
        if (auth()->user()->isSuperAdmin() && session()->get('active_user_group') === 'all') {
            return Account::query();
        }

        return $this->hasMany(Account::class);
    }

    public function budgets(): HasMany | Builder
    {
        if (auth()->user()->isSuperAdmin() && session()->get('active_user_group') === 'all') {
            return Budget::query();
        }

        return $this->hasMany(Budget::class);
    }

    public function bills(): HasMany | Builder
    {
        if (auth()->user()->isSuperAdmin() && session()->get('active_user_group') === 'all') {
            return Bill::query();
        }

        return $this->hasMany(Bill::class);
    }

    public function availableBudgets(): HasMany | Builder
    {
        if (auth()->user()->isSuperAdmin() && session()->get('active_user_group') === 'all') {
            return AvailableBudget::query();
        }

        return $this->hasMany(AvailableBudget::class);
    }

    public function categories(): HasMany | Builder
    {
        if (auth()->user()->isSuperAdmin() && session()->get('active_user_group') === 'all') {
            return Category::query();
        }

        return $this->hasMany(Category::class);
    }

    public function rules(): HasMany | Builder
    {
        if (auth()->user()->isSuperAdmin() && session()->get('active_user_group') === 'all') {
            return Rule::query();
        }

        return $this->hasMany(Rule::class);
    }

    public function tags(): HasMany | Builder
    {
        if (auth()->user()->isSuperAdmin() && session()->get('active_user_group') === 'all') {
            return Tag::query();
        }

        return $this->hasMany(Tag::class);
    }


    public function transactions(): HasManyThrough | Builder
    {
        if (auth()->user()->isSuperAdmin() && session()->get('active_user_group') === 'all') {
            return Transaction::leftJoin('transaction_journals', 'transactions.transaction_journal_id', '=', 'transaction_journals.id');
        }

        return $this->hasManyThrough(Transaction::class, TransactionJournal::class);
    }

    public function transactionJournals(): HasMany | Builder
    {
        if (auth()->user()->isSuperAdmin() && session()->get('active_user_group') === 'all') {
            return TransactionJournal::query();
        }

        return $this->hasMany(TransactionJournal::class);
    }

    public function piggyBanks(): HasManyThrough | Builder
    {        
        if (auth()->user()->isSuperAdmin() && session()->get('active_user_group') === 'all') {
            return PiggyBank::query();
        }

        return $this->hasManyThrough(PiggyBank::class, Account::class);
    }

    public function ruleGroups(): HasMany | Builder
    {
        if (auth()->user()->isSuperAdmin() && session()->get('active_user_group') === 'all') {
            return RuleGroup::query();
        }

        return $this->hasMany(RuleGroup::class);
    }

    public function webhooks(): HasMany | Builder
    {
        if (auth()->user()->isSuperAdmin() && session()->get('active_user_group') === 'all') {
            return Webhook::query();
        }

        return $this->hasMany(Webhook::class);
    }

    public function objectGroups(): HasMany | Builder
    {
        if (auth()->user()->isSuperAdmin() && session()->get('active_user_group') === 'all') {
            return ObjectGroup::query();
        }

        return $this->hasMany(ObjectGroup::class);
    }

    public function recurrences(): HasMany | Builder
    {
        if (auth()->user()->isSuperAdmin() && session()->get('active_user_group') === 'all') {
            return Recurrence::query();
        }

        return $this->hasMany(Recurrence::class);
    }
}
