<?php

declare(strict_types=1);

namespace FireflyIII\Http\Controllers\Member;

use FireflyIII\Http\Controllers\Controller;
use FireflyIII\Models\Permission;

/**
 * Class Member\IndexController.
 */
class CreateController extends Controller
{
    public function create()
    {
        $subTitle     = (string) trans('firefly.add_member');
        $subTitleIcon = 'fa-user-o';
        $previousUrl = route('members.index');
        $permissions = Permission::all(['name', 'id']);

        return view('members.create', compact('permissions', 'subTitle', 'subTitleIcon', 'previousUrl'));
    }
}
