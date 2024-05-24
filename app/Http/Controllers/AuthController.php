<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ThrottlesAttempts;
use App\Http\Helpers\RequestHelper;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Exceptions\OAuthServerException;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Authentication
 */
class AuthController extends AccessTokenController
{
    use ThrottlesAttempts;

    /**
     * Login
     * 
     * @bodyParam email string required Example: testing@gmail.com
     * @bodyParam password string required Example: testing
     */
    public function login(Request $request_http): array
    {
        $this->validateAttempts($request_http);

        $request = RequestHelper::createServerRequest($request_http);
        $body = $request->getParsedBody();
        $body['client_id'] = config('passport.clients.users.id');
        $body['client_secret'] = config('passport.clients.users.secret');
        $body['grant_type'] = 'password';
        $body['username'] = $body['email'];
        $body['scope'] = '';

        try {
            $result = json_decode($this->issueToken($request->withParsedBody($body))->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $this->clearAttempts($request_http);
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (OAuthServerException | Exception $exception) {
            $this->incrementAttempts($request_http);
            throw new BadRequestHttpException(__('error.incorrect_credentials'));
        }

        return [
            'data' => $result,
            'message' => __('success.login_success')
        ];
    }

    /**
     * Register
     * 
     * @bodyParam name string required Example: Zap
     * @bodyParam email string required Example: zap@gmail.com
     * @bodyParam password string required Example: zap123
     * @bodyParam phone string required Example: 0898328988232
     * @bodyParam address string required Example: Jln. Gatot
     */
    public function register(ServerRequestInterface $request): JsonResponse
    {
        $body = $request->getParsedBody();

        $validator = validator($body, [
            'name' => 'required|string',
            'email' => 'required|email:rfc,dns',
            'password' => ['required', Password::min(6)->letters()->numbers()],
            'phone' => ['required', 'string', 'phone:ID', Rule::unique('users', 'phone')],
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->messages());
        }

        $user = $this->checkUserExists($body);

        if ($user !== null && $user->password !== null) {
            throw ValidationException::withMessages([
                'email' => [__('error.register_fail_email_exists')],
            ]);
        }

        DB::beginTransaction();
        try {
            $user = new User($body);
            $user->password = Hash::make($body['password']);
            $user->saveOrFail();

            // TODO: send verification code to verify user!

            DB::commit();
        } catch (Exception) {
            DB::rollBack();
            throw new BadRequestHttpException(__('error.register_fail'));
        }

        return response()->json([
            'message' => __('success.register_success'),
        ]);
    }

    private function checkUserExists(array $input): ?User
    {
        try {
            return User::where('email', $input['email'])
                ->firstOrFail();
        } catch (Exception) {
            return null;
        }
    }
}
