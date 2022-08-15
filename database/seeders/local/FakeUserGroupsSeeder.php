<?php

declare(strict_types=1);

namespace Database\Seeders\Local;

use Illuminate\Database\Seeder;
use Log;
use FireflyIII\Models\UserGroup;
use Faker\Factory;

/**
 * Class UserRoleSeeder
 */
class FakeUserGroupsSeeder extends Seeder
{
  public function run()
  {
    Log::debug('Store new fake user group');

    $faker = Factory::create();
    $fakeData = [];

    UserGroup::where('title', 'like', '%Fake%')->forceDelete();

    for ($i = 0; $i < 10; $i++) {
      $createdAt = $faker->dateTime();
      $updatedAt = $faker->dateTimeInInterval($createdAt, '-5 years');

      $fakeData[] = [
        'title' => $faker->company() . ' Fake Company',
        'created_at' => $createdAt,
        'updated_at' => $updatedAt,
      ];
    }

    UserGroup::insert($fakeData);
  }
}
