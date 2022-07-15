<?php

declare(strict_types=1);

namespace FireflyIII\Repositories\GroupMembership;

use FireflyIII\User;
use FireflyIII\Models\GroupMembership;

class GroupMembershipRepository implements GroupMembershipRepositoryInterface
{
  public function setUser(User $user): void
  {
    $this->user = $user;
  }
}
