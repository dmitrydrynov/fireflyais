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
use Log;

/**
 * Class PermissionsSeeder.
 */
class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            /* accounts */
            ['name' => 'accounts'],
            ['name' => 'accounts.create'],
            ['name' => 'accounts.create.asset'],
            ['name' => 'accounts.create.expense'],
            ['name' => 'accounts.create.revenue'],
            ['name' => 'accounts.create.liabilities'],
            ['name' => 'accounts.read'],
            ['name' => 'accounts.read.asset'],
            ['name' => 'accounts.read.expense'],
            ['name' => 'accounts.read.revenue'],
            ['name' => 'accounts.read.liabilities'],
            ['name' => 'accounts.update'],
            ['name' => 'accounts.update.asset'],
            ['name' => 'accounts.update.expense'],
            ['name' => 'accounts.update.revenue'],
            ['name' => 'accounts.update.liabilities'],
            ['name' => 'accounts.delete'],
            ['name' => 'accounts.delete.asset'],
            ['name' => 'accounts.delete.expense'],
            ['name' => 'accounts.delete.revenue'],
            ['name' => 'accounts.delete.liabilities'],
            ['name' => 'accounts.recancile'],
            /* attachments */
            ['name' => 'attachments'],
            ['name' => 'attachments.download'],
            ['name' => 'attachments.read'],
            ['name' => 'attachments.update'],
            ['name' => 'attachments.delete'],
            /* bills */
            ['name' => 'bills'],
            ['name' => 'bills.create'],
            ['name' => 'bills.read'],
            ['name' => 'bills.update'],
            ['name' => 'bills.delete'],
            ['name' => 'bills.setOrder'],
            /* subscriptions */
            ['name' => 'subscriptions'],
            ['name' => 'subscriptions.create'],
            ['name' => 'subscriptions.read'],
            ['name' => 'subscriptions.update'],
            ['name' => 'subscriptions.delete'],
            ['name' => 'subscriptions.setOrder'],
            /* budgets */
            ['name' => 'budgets'],
            ['name' => 'budgets.create'],
            ['name' => 'budgets.read'],
            ['name' => 'budgets.update'],
            ['name' => 'budgets.delete'],
            ['name' => 'budgets.setOrder'],
            /* available-budgets */
            ['name' => 'available-budgets'],
            ['name' => 'available-budgets.create'],
            ['name' => 'available-budgets.read'],
            ['name' => 'available-budgets.update'],
            ['name' => 'available-budgets.delete'],
            /* budget-limits */
            ['name' => 'budget-limits'],
            ['name' => 'budget-limits.create'],
            ['name' => 'budget-limits.read'],
            ['name' => 'budget-limits.update'],
            ['name' => 'budget-limits.delete'],
            /* categories */
            ['name' => 'categories'],
            ['name' => 'categories.create'],
            ['name' => 'categories.read'],
            ['name' => 'categories.update'],
            ['name' => 'categories.delete'],
            /* Charts */
            ['name' => 'chart'],
            ['name' => 'chart.accounts.read'],
            ['name' => 'chart.bills.read'],
            ['name' => 'chart.budgets.read'],
            ['name' => 'chart.categories.read'],
            ['name' => 'chart.tags.read'],
            ['name' => 'chart.double.read'],
            ['name' => 'chart.piggybank.read'],
            ['name' => 'chart.transactions.read'],
            /* Export data */
            ['name' => 'export-data'],
            /* object-groups */
            ['name' => 'object-groups'],
            ['name' => 'object-groups.create'],
            ['name' => 'object-groups.read'],
            ['name' => 'object-groups.update'],
            ['name' => 'object-groups.delete'],
            /* piggy-banks */
            ['name' => 'piggy-banks'],
            ['name' => 'piggy-banks.create'],
            ['name' => 'piggy-banks.read'],
            ['name' => 'piggy-banks.update'],
            ['name' => 'piggy-banks.delete'],
            ['name' => 'piggy-banks.setOrder'],
            /* piggy-banks */
            ['name' => 'preferences'],
            ['name' => 'preferences.read'],
            ['name' => 'preferences.update'],
            /* recurring */
            ['name' => 'recurring'],
            ['name' => 'recurring.create'],
            ['name' => 'recurring.read'],
            ['name' => 'recurring.update'],
            ['name' => 'recurring.delete'],
            /* reports */
            ['name' => 'reports'],
            ['name' => 'reports.report'],
            /* rules */
            ['name' => 'rules'],
            ['name' => 'rules.create'],
            ['name' => 'rules.read'],
            ['name' => 'rules.update'],
            ['name' => 'rules.delete'],
            /* rule-groups */
            ['name' => 'rule-groups'],
            ['name' => 'rule-groups.create'],
            ['name' => 'rule-groups.read'],
            ['name' => 'rule-groups.update'],
            ['name' => 'rule-groups.delete'],
            ['name' => 'rule-groups.execute'],
            ['name' => 'rule-groups.move'],
            /* tags */
            ['name' => 'tags'],
            ['name' => 'tags.create'],
            ['name' => 'tags.read'],
            ['name' => 'tags.update'],
            ['name' => 'tags.delete'],
            /* transactions */
            ['name' => 'transactions'],
            ['name' => 'transactions.create'],
            ['name' => 'transactions.create.withdrawal'],
            ['name' => 'transactions.create.deposit'],
            ['name' => 'transactions.create.transfer'],
            ['name' => 'transactions.read'],
            ['name' => 'transactions.update'],
            ['name' => 'transactions.update.withdrawal'],
            ['name' => 'transactions.update.deposit'],
            ['name' => 'transactions.update.transfer'],
            ['name' => 'transactions.delete'],
            ['name' => 'transactions.delete.withdrawal'],
            ['name' => 'transactions.delete.deposit'],
            ['name' => 'transactions.delete.transfer'],
            ['name' => 'transactions.mass'],
            ['name' => 'transactions.bulk'],
            ['name' => 'transactions.convert'],
            ['name' => 'transactions.link'],
            /* webhooks */
            ['name' => 'webhooks'],
            ['name' => 'webhooks.read'],
            /* admin */
            ['name' => 'admin'],
            ['name' => 'admin.test-message'],
            ['name' => 'admin.journalLinks.create'],
            ['name' => 'admin.journalLinks.read'],
            ['name' => 'admin.journalLinks.update'],
            ['name' => 'admin.journalLinks.delete'],
            ['name' => 'admin.configuration.read'],
            ['name' => 'admin.configuration.update'],
            /* users */
            ['name' => 'users'],
            ['name' => 'users.create'],
            ['name' => 'users.read'],
            ['name' => 'users.update'],
            ['name' => 'users.delete'],
            ['name' => 'users.setAccess'],
            ['name' => 'users.createNewUser'],
        ];

        foreach ($permissions as $permission) {
            try {
                Permission::create($permission);
            } catch (\Throwable $e) {
                // @ignoreException
                Log::error($e->getMessage());
            }
        }
    }
}
