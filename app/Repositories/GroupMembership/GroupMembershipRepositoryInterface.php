<?php

declare(strict_types=1);

namespace FireflyIII\Repositories\GroupMembership;

use FireflyIII\User;
use FireflyIII\Models\GroupMembership;

/**
 * Interface GroupMembershipRepositoryInterface
 *
 * @package FireflyIII\Repositories\GroupMembership
 */
interface GroupMembershipRepositoryInterface
{
    public function setUser(User $user): void;
}
