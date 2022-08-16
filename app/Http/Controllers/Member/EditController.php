<?php

declare(strict_types=1);

namespace FireflyIII\Http\Controllers\Member;

use FireflyIII\Http\Controllers\Controller;
use FireflyIII\User;
use Illuminate\Http\Request;
use FireflyIII\Models\Permission;

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
    public function edit(Request $request, User $member)
    {
        $subTitle     = (string) trans('firefly.edit_member');
        $subTitleIcon = 'fa-user-o';
        $previousUrl = route('members.index');

        $permissions = Permission::all(['name', 'id']);

        // setPermissionsTeamId($request->user()->user_group_id);
        $userPermissions = $member->getPermissionNames();

        return view('members.edit', compact('member', 'userPermissions', 'permissions', 'subTitle', 'subTitleIcon', 'previousUrl'));
    }
}
