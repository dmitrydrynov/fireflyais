<?php

/**
 * PermissionSeeder.php
 * Copyright (c) 2019 james@firefly-iii.org.
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

namespace Database\Seeders;

use FireflyIII\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;
use Log;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionsSeeder.
 */
class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $arrayOfPermissionNames = [
            /* accounts */
            'accounts',
            'accounts.create',
            'accounts.create.asset',
            'accounts.create.expense',
            'accounts.create.revenue',
            'accounts.create.liabilities',
            'accounts.read',
            'accounts.read.asset',
            'accounts.read.expense',
            'accounts.read.revenue',
            'accounts.read.liabilities',
            'accounts.update',
            'accounts.update.asset',
            'accounts.update.expense',
            'accounts.update.revenue',
            'accounts.update.liabilities',
            'accounts.delete',
            'accounts.delete.asset',
            'accounts.delete.expense',
            'accounts.delete.revenue',
            'accounts.delete.liabilities',
            'accounts.recancile',
            /* bills */
            'bills',
            'bills.create',
            'bills.read',
            'bills.update',
            'bills.delete',
            'bills.setOrder',
            /* subscriptions */
            'subscriptions',
            'subscriptions.create',
            'subscriptions.read',
            'subscriptions.update',
            'subscriptions.delete',
            'subscriptions.setOrder',
            /* budgets */
            'budgets',
            'budgets.create',
            'budgets.read',
            'budgets.update',
            'budgets.delete',
            'budgets.setOrder',
            /* available-budgets */
            'available-budgets',
            'available-budgets.create',
            'available-budgets.read',
            'available-budgets.update',
            'available-budgets.delete',
            /* budget-limits */
            'budget-limits',
            'budget-limits.create',
            'budget-limits.read',
            'budget-limits.update',
            'budget-limits.delete',
            /* categories */
            'categories',
            'categories.create',
            'categories.read',
            'categories.update',
            'categories.delete',
            /* Charts */
            'chart',
            'chart.accounts.read',
            'chart.bills.read',
            'chart.budgets.read',
            'chart.categories.read',
            'chart.tags.read',
            'chart.double.read',
            'chart.piggybank.read',
            'chart.transactions.read',
            /* Export data */
            'export-data',
            /* object-groups */
            'object-groups',
            'object-groups.create',
            'object-groups.read',
            'object-groups.update',
            'object-groups.delete',
            /* piggy-banks */
            'piggy-banks',
            'piggy-banks.create',
            'piggy-banks.read',
            'piggy-banks.update',
            'piggy-banks.delete',
            'piggy-banks.setOrder',
            /* piggy-banks */
            'preferences',
            'preferences.read',
            'preferences.update',
            /* recurring */
            'recurring',
            'recurring.create',
            'recurring.read',
            'recurring.update',
            'recurring.delete',
            /* reports */
            'reports',
            'reports.report',
            /* rules */
            'rules',
            'rules.create',
            'rules.read',
            'rules.update',
            'rules.delete',
            'rules.execute',
            /* tags */
            'tags',
            'tags.create',
            'tags.read',
            'tags.update',
            'tags.delete',
            /* transactions */
            'transactions',
            'transactions.create',
            'transactions.read',
            'transactions.update',
            'transactions.delete',
            'transactions.convert',
            'transactions.link',
            /* webhooks */
            'webhooks',
            'webhooks.read',
            /* members */
            'members',
            'members.create',
            'members.read',
            'members.update',
            'members.delete',
        ];

        $permissions = collect($arrayOfPermissionNames)->map(
            function ($permission) {
                return ['name' => $permission, 'guard_name' => '*'];
            }
        );

        foreach ($permissions->toArray() as $permission) {
            try {
                // Reset cached roles and permissions
                app()[PermissionRegistrar::class]->forgetCachedPermissions();

                Permission::create($permission);
            } catch (\Throwable $e) {
                // @ignoreException
                Log::error($e->getMessage());
            }
        }

        $dbPermissions = Permission::get();
        foreach ($dbPermissions as $dbPermission) {
            try {
                if (!in_array($dbPermission->name, $arrayOfPermissionNames)) {
                    $dbPermission->delete();
                }
            } catch (\Throwable $e) {
                // @ignoreException
                Log::error($e->getMessage());
            }
        }
    }
}
