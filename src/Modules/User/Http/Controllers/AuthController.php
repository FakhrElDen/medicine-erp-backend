<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Cache;
use Modules\User\Entities\User;
use Modules\User\Http\Requests\LoginRequest;
use Modules\User\Transformers\UserResource;

class AuthController extends BaseController
{
    public function login(LoginRequest $request)
    {
        $credentials = auth()->attempt($request->only('email', 'password'));

        if ($credentials == false) {
            return $this->apiErrorResponse(message: trans('user::message.login_message'), status_code: 401);
        }

        /** @var User $user */
        $user = auth()->user();

        return $this->apiResponse([
            'token' => $this->generateNewToken($user),
            'token_expiration' => collect(Cache::get('settings'))->firstWhere('key', 'minutes_expiration_session')->value,
            'user' => new UserResource($user),
        ]);
    }

    protected function generateNewToken(User $user)
    {
        if (!config('custom.enable_multiple_logins')) {
            $user->tokens()->delete();
        }

        return $user->createToken(config('app.name'))->plainTextToken;
    }

    public function logout()
    {
        // using logout() method to trigger the event for login logout of users
        auth()->guard('web')->logout();

        /** @var User $user */
        $user = auth()->user();

        /** @var $token */
        $token = $user->currentAccessToken();
        $token->delete();

        return $this->apiResponse(message: trans('user::message.logout_message'));
    }
}
