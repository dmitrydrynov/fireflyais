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

use FireflyIII\Models\Role;
use Illuminate\Database\Seeder;
use Log;

/**
 * Class RoleSeeder.
 */
class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name'         => 'superadmin',
                'display_name' => 'Super Admin',
                'description'  => 'User runs this instance of FF3',
            ],
            [
                'name'         => 'owner',
                'display_name' => 'Site Owner',
                'description'  => 'User has full access to his user group',
                'permissions' => [
                    'accounts',
                    'attachments',
                    'bills',
                    'subscriptions',
                    'budgets',
                    'available-budgets',
                    'budget-limits',
                    'categories',
                    'chart',
                    'export-data',
                    'object-groups',
                    'piggy-banks',
                    'preferences',
                    'recurring',
                    'reports',
                    'rules',
                    'rule-groups',
                    'tags',
                    'transactions',
                    'webhooks',
                    'members',
                ],
            ],
            [
                'name'         => 'demo',
                'display_name' => 'Demo User',
                'description'  => 'User is a demo user',
            ],
        ];

        foreach ($roles as $role) {
            try {
                $newRole = Role::create(['name' => $role['name'], 'display_name' => $role['display_name'], 'description' => $role['description']]);

                if (isset($role['permissions'])) {
                    $newRole->syncPermissions($role['permissions']);
                }
            } catch (\Throwable $e) {
                // @ignoreException
                Log::error($e->getMessage());
            }
        }
    }
}
