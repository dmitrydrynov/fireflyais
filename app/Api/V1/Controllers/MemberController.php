<?php

declare(strict_types=1);

namespace FireflyIII\Api\V1\Controllers;

use FireflyIII\Api\V1\Controllers\Controller;
use FireflyIII\Api\V1\Requests\Models\Budget\StoreRequest;
use FireflyIII\Exceptions\FireflyException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;
use Mail;
use FireflyIII\User;
use FireflyIII\Mail\UserJoined;
use FireflyIII\Models\Permission;
use Str;

/**
 * Class StoreController
 */
class MemberController extends Controller
{
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
            $permissionsNames = (array)$request->get('permissions');
            $permissions = Permission::whereIn('name', $permissionsNames)->get();

            // Create user
            $member = User::create(
                [
                    'blocked'      => false,
                    'email'        => $email,
                    'password'     => Str::random(24),
                    'user_group_id' => $request->user()->user_group_id || null,
                ]
            );

            $_passwordRaw = $member->password;

            $member->password = bcrypt($member->password);
            $member->user_group_id = $request->user()->user_group_id;
            $member->save();

            // setPermissionsTeamId($request->user()->user_group_id);
            $member->givePermissionTo($permissions);
            $member->assignRole('member');

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
            $permissionsNames = (array)$request->get('permissions');
            $permissions = Permission::whereIn('name', $permissionsNames)->get();

            $member = User::find($id);

            // setPermissionsTeamId($request->user()->user_group_id);
            $member->syncPermissions($permissions);

            return response()->json(['success' => true]);
        } catch (Throwable $exception) {
            return response()->json([
                'error' => 'Error: ' . $exception->getMessage(),
                'code' => $exception->getCode()
            ], 404);
        }
    }
}
