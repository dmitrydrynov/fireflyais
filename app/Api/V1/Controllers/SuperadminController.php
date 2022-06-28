<?php

declare(strict_types=1);

namespace FireflyIII\Api\V1\Controllers;

use FireflyIII\Api\V1\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

/**
 * Class StoreController
 */
class SuperadminController extends Controller
{
    public function switchUserGroup(Request $request): JsonResponse
    {
        try {
            $userGroupId = $request->get('userGroupId');

            if ($userGroupId === 'all') {
                // 
            } else if (is_int($userGroupId)) {
                $request->user()->switchToUserGroup((int)$userGroupId);
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
