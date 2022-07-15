<?php

declare(strict_types=1);

namespace FireflyIII\Http\Controllers\Member;

use FireflyIII\Http\Controllers\Controller;

/**
 * Class Member\IndexController.
 */
class IndexController extends Controller
{
    public function index()
    {
        app('view')->share('title', (string) trans('firefly.list_all_members'));
        app('view')->share('mainTitleIcon', 'fa-users');

        $members = auth()->user()->userGroup->members;

        $members = $members->filter(function ($member) {
            return $member->isSuperAdmin() && !$member->hasRole('owner') ? false : true;
        });

        return view('members.index', compact('members'));
    }
}
