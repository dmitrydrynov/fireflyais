<?php

declare(strict_types=1);

namespace Database\Seeders\Local;

use Illuminate\Database\Seeder;
use Log;
use FireflyIII\User;
use FireflyIII\Models\UserGroup;
use Faker\Factory;
use FireflyIII\Support\Http\Controllers\CreateStuff;
use FireflyIII\Http\Requests\NewUserFormRequest;
use FireflyIII\Repositories\Currency\CurrencyRepositoryInterface;
use FireflyIII\Factory\AccountFactory;

/**
 * Class UserRoleSeeder
 */
class FakeUsersSeeder extends Seeder
{
  use CreateStuff;

  public function run()
  {
    Log::debug('Store new fake users');

    $faker = Factory::create();

    $fakeUserGroups = UserGroup::where('title', 'like', '%Fake%')->get();

    if (count($fakeUserGroups) > 0) foreach ($fakeUserGroups as $fakeUserGroup) {
      // clean old fake users
      $fakeUserGroup->hasMany(User::class)->forceDelete();

      // make owner
      $ownerEmail = $faker->email();
      $owner = User::create([
        "email" => $ownerEmail,
        'password'     => bcrypt($ownerEmail),
        'user_group_id' => $fakeUserGroup->id,
      ]);

      setPermissionsTeamId($fakeUserGroup->id);
      $owner->assignRole(['owner']);

      $request = new NewUserFormRequest([
        'bank_name' => 'DDD',
        'bank_balance' => 'TTT',
      ]);

      $currencyRepository = app(CurrencyRepositoryInterface::class);
      $currency = $currencyRepository->findByCodeNull('EUR');

      $accountFactory = app(AccountFactory::class);
      $accountFactory->setUser($owner);

      $this->createAssetAccount($request, $currency);              // create normal asset account
      $this->createSavingsAccount($request, $currency, 'en_US'); // create savings account
      $this->createCashWalletAccount($currency, 'en_US');        // create cash wallet account

      // make members
      for ($i = 0; $i < 5; $i++) {
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
