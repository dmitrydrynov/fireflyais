<?php

declare(strict_types=1);

namespace FireflyIII\Api\V1\Controllers;

use FireflyIII\Api\V1\Controllers\Controller;
use FireflyIII\Api\V1\Requests\Models\Budget\StoreRequest;
use FireflyIII\Exceptions\FireflyException;
use FireflyIII\Models\GroupMembership;
use FireflyIII\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;
use Mail;
use FireflyIII\Models\UserRole;
use FireflyIII\Mail\UserJoined;

/**
 * Class StoreController
 */
class MemberController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware(
            function ($request, $next) {
                $this->userRepository = app(UserRepositoryInterface::class);

                return $next($request);
            }
        );
    }

    /**
     * Store a member.
     *
     * @param StoreRequest $request
     *
     * @return JsonResponse
     * @throws FireflyException
     *
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $_passwordRaw = null;
            $email = $request->get('email');
            $permissions = $request->get('permissions');

            // Create user
            $user = $this->userRepository->store(['email' => $email]);

            $_passwordRaw = $user->password;

            $user->password = bcrypt($user->password);
            $user->user_group_id = auth()->user()->user_group_id;
            $user->save();

            $userRoles = UserRole::whereIn('title', $permissions)->get();

            if (count($userRoles) > 0) foreach ($userRoles as $userRole) {
                GroupMembership::create(['user_id' => $user->id, 'user_role_id' => $userRole->id, 'user_group_id' => $user->user_group_id]);
            }

            Mail::to($email)->send(new UserJoined($email, $_passwordRaw));

            return response()->json(['success' => true]);
        } catch (Throwable $exception) {
            return response()->json([
                'error' => 'Error: ' . $exception->getMessage(),
                'code' => $exception->getCode()
            ], 404);
        }
    }


    public function update(Request $request): JsonResponse
    {
        try {
            $id = $request->get('id');
            $permissions = $request->get('permissions');

            $user = $this->userRepository->find($id);
            $userRoles = UserRole::whereIn('title', $permissions)->get();

            // remove old user group roles
            $user->groupMemberships($user->user_group_id)->delete();

            // add new ones
            if (count($userRoles) > 0) foreach ($userRoles as $userRole) {
                GroupMembership::create(['user_id' => $user->id, 'user_role_id' => $userRole->id, 'user_group_id' => $user->user_group_id]);
            }

            return response()->json(['success' => true]);
        } catch (Throwable $exception) {
            return response()->json([
                'error' => 'Error: ' . $exception->getMessage(),
                'code' => $exception->getCode()
            ], 404);
        }
    }
}
