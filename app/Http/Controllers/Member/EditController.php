<?php

declare(strict_types=1);

namespace FireflyIII\Http\Controllers\Member;

use FireflyIII\Http\Controllers\Controller;
use FireflyIII\Models\Member;
use Illuminate\Http\Request;

/**
 * Class Member\IndexController.
 */
class EditController extends Controller
{
    /**
     * Edit a member.
     *
     * @param Request $request
     * @param Member    $member
     *
     * @return Factory|View
     */
    public function edit(Request $request, Member $member)
    {
        $subTitle     = (string) trans('firefly.edit_member');
        $subTitleIcon = 'fa-user-o';
        $previousUrl = route('members.index');

        $permissions = $member->getPermissions();

        return view('members.edit', compact('member', 'permissions', 'subTitle', 'subTitleIcon', 'previousUrl'));
    }
}
