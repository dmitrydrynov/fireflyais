<?php

declare(strict_types=1);

namespace FireflyIII\Http\Controllers\Member;

use FireflyIII\Http\Controllers\Controller;
use FireflyIII\Models\GroupMembership;

/**
 * Class Member\IndexController.
 */
class CreateController extends Controller
{
    public function create()
    {
        $subTitle     = (string) trans('firefly.add_member');
        $subTitleIcon = 'fa-user-o';
        $member = new GroupMembership();
        $previousUrl = route('members.index');

        return view('members.create', compact('member', 'subTitle', 'subTitleIcon', 'previousUrl'));
    }
}
