<?php

declare(strict_types=1);

namespace FireflyIII\Http\Controllers\Member;

use FireflyIII\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use FireflyIII\Repositories\User\UserRepositoryInterface;

/**
 * Class DeleteController
 */
class DeleteController extends Controller
{
  private UserRepositoryInterface $repository;

  public function __construct()
  {
    parent::__construct();

    $this->middleware(
      function ($request, $next) {
        app('view')->share('title', (string) trans('firefly.piggyBanks'));
        app('view')->share('mainTitleIcon', 'fa-bullseye');

        $this->repository = app(UserRepositoryInterface::class);

        return $next($request);
      }
    );
  }

  /**
   * @param User $member
   *
   * @return Application|Factory|RedirectResponse|Redirector|View
   */
  public function delete(User $member)
  {
    $title = (string) trans('firefly.delete_user', ['email' => $member->email]);

    return view('members.delete', compact('member', 'title'));
  }

  /**
   * Destroy the member (user and his all group memberships).
   *
   * @param User $member
   *
   * @return RedirectResponse
   */
  public function destroy(User $member): RedirectResponse
  {
    $this->repository->destroy($member);
    session()->flash('success', (string) trans('firefly.member_deleted'));

    return redirect(route('members.index'));
  }
}
