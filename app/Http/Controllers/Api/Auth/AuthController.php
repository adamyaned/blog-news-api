<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\User\UserResource;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private CommentRepositoryInterface $commentRepository,
    )
    {

    }

    public function register(RegisterRequest $request): SuccessResource
    {
        $this->userRepository->create($request->validated());

        return SuccessResource::make([
            'message' => 'Successfully registered'
        ]);
    }

    public function login(LoginRequest $request): SuccessResource|ErrorResource
    {
        $data = $request->validated();

        if (!Auth::attempt($data)) {
            return ErrorResource::make([
                'message' => trans('auth.failed')
            ]);
        }

        $user = $this->userRepository->getByEmail($data['email']);

        return SuccessResource::make([
            'data' => [
                'user' => UserResource::make($user),
                'auth' => [
                    'token' => $user->createToken('access_token')->plainTextToken,
                ]
            ]
        ]);
    }

    public function user(): SuccessResource
    {
        return SuccessResource::make([
            'data' => [
                'user' => UserResource::make(
                    $this->userRepository->getById(auth()->id())
                ),
            ]
        ]);
    }

    public function refresh(): SuccessResource
    {
        $user = $this->userRepository->getById(auth()->id());
        $user->tokens()->delete();

        return SuccessResource::make([
            'data' => [
                'user' => UserResource::make($user),
                'auth' => [
                    'token' => $user->createToken('access_token')->plainTextToken,
                ]
            ]
        ]);
    }

    public function logout(Request $request): SuccessResource
    {
        auth()->user()->tokens()->delete();

        return SuccessResource::make([
            'message' => 'Successfully logged out'
        ]);
    }
}
