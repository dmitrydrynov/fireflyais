<?php

declare(strict_types=1);

namespace Database\Seeders\Local;

use Illuminate\Database\Seeder;
use Log;
use FireflyIII\User;
use FireflyIII\Models\UserGroup;
use Faker\Factory;

/**
 * Class UserRoleSeeder
 */
class FakeUsersSeeder extends Seeder
{
  public function run()
  {
    $faker = Factory::create();

    Log::debug('Store new fake users');

    $jsonData = file_get_contents(__DIR__ . '/data/users.json');
    $defaultUsers = json_decode($jsonData, true);

    // fetch default owners for 2,3,4 fake companies
    foreach ($defaultUsers['owners'] as $owner) {
      $oldRecord = User::find($owner['id']);
      if ($oldRecord) {
        $oldRecord->forceDelete();
      }

      $_owner = User::create([
        "id" => $owner['id'],
        "email" => $owner['email'],
        'password'     => bcrypt($owner['password'],),
        'user_group_id' => $owner['user_group_id'],
      ]);

      setPermissionsTeamId($owner['user_group_id']);
      $_owner->assignRole(['owner']);
    }

    // fetch default members for 2,3,4 fake companies
    foreach ($defaultUsers['members'] as $member) {
      $oldRecord = User::find($member['id']);
      if ($oldRecord) {
        $oldRecord->forceDelete();
      }

      $_member = User::create([
        "id" => $member['id'],
        "email" => $member['email'],
        'password'     => bcrypt($member['password'],),
        'user_group_id' => $member['user_group_id'],
      ]);

      $_member->givePermissionTo($member['permissions']);
      $_member->assignRole('member');
    }

    // fetch other fake users
    $fakeUserGroups = UserGroup::where('title', 'like', '%Fake%')->whereNotIn('id', [2, 3, 4])->get();

    if (count($fakeUserGroups) > 0) foreach ($fakeUserGroups as $fakeUserGroup) {
      // clean old fake users
      foreach ($fakeUserGroup->hasMany(User::class)->get() as $oldUser) {
        $oldUser->forceDelete();
      }

      // make owner
      $ownerEmail = $faker->email();
      $owner = User::create([
        "email" => $ownerEmail,
        'password'     => bcrypt($ownerEmail),
        'user_group_id' => $fakeUserGroup->id,
      ]);

      // $currency = app(CurrencyRepositoryInterface::class)->findByCodeNull('EUR');
      // $this->createAssetAccount(new NewUserFormRequest(['bank_name', $fakeUserGroup->name + ' Bank']), $currency);

      // app('preferences')->set('language', 'en_US');
      // app('preferences')->set('currencyPreference', 'EUR');
      // // app('preferences')->set('frontPageAccounts', $accounts);
      // app('preferences')->set('transaction_journal_optional_fields', [
      //   'interest_date' => true, 'book_date' => false, 'process_date' => false, 'due_date' => false, 'payment_date' => false,
      //   'invoice_date'  => false, 'internal_reference' => false, 'notes' => true, 'attachments' => true,
      // ]);
      // app('preferences')->mark();

      setPermissionsTeamId($fakeUserGroup->id);
      $owner->assignRole(['owner']);

      // make members
      for ($i = 0; $i < 3; $i++) {
        $memberEmail = $faker->email();

        $member = User::create([
          "email" => $memberEmail,
          'password'     => bcrypt($memberEmail),
          'user_group_id' => $fakeUserGroup->id,
        ]);
        $member->givePermissionTo(['accounts', 'transactions']);
        $member->assignRole('member');
      }
    }
  }
}
