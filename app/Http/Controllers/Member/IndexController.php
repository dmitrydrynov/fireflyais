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

        $types = config('firefly.accountTypesByIdentifier.asset');
        $languages = [];

        $members = auth()->user()->getMembers()->get();

        return view('members.index', compact('members'));
    }
}
