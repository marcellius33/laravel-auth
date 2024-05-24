<?php

namespace App\Http\Controllers;

use App\Http\Helpers\RequestHelper;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Socialite
 */
class SocialiteController extends AccessTokenController
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback(Request $request_http): array
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            $user = User::where('facebook_id', $facebookUser->id)->first();

            if (is_null($user)) {
                $user = new User([
                    'name' => $facebookUser->name,
                    'email' => $facebookUser->email,
                    'facebook_id' => $facebookUser->id,
                    'password' => encrypt('facebook_password')
                ]);
                $user->save();
            }

            // TODO: Not Tested
            $request = RequestHelper::createServerRequest($request_http);
            $body['client_id'] = config('passport.clients.users.id');
            $body['client_secret'] = config('passport.clients.users.secret');
            $body['grant_type'] = 'password';
            $body['username'] = $user->email;
            $body['scope'] = '';

            $result = json_decode($this->issueToken($request->withParsedBody($body))->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            throw new BadRequestHttpException(__('error.oauth_fail'));
        }

        return [
            'data' => $result,
            'message' => __('success.login_success')
        ];
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request_http): array
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('google_id', $$googleUser->id)->first();

            if (is_null($user)) {
                $user = new User([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => encrypt('google_password')
                ]);
                $user->save();
            }

            // TODO: Not Tested
            $request = RequestHelper::createServerRequest($request_http);
            $body['client_id'] = config('passport.clients.users.id');
            $body['client_secret'] = config('passport.clients.users.secret');
            $body['grant_type'] = 'password';
            $body['username'] = $user->email;
            $body['scope'] = '';

            $result = json_decode($this->issueToken($request->withParsedBody($body))->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            throw new BadRequestHttpException(__('error.oauth_fail'));
        }

        return [
            'data' => $result,
            'message' => __('success.login_success')
        ];
    }
}
