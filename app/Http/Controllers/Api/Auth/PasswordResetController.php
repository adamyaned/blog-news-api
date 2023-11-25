<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Mail\ResetPassword;
use App\Repositories\Interfaces\PasswordResetRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    public function __construct(
        private PasswordResetRepositoryInterface $passwordResetRepository,
        private UserRepositoryInterface          $userRepository,
    )
    {

    }

    public function forgotPassword(ForgotPasswordRequest $request): SuccessResource|ErrorResource
    {
        $data = $request->validated();
        $code = $this->passwordResetRepository->findByEmail($data['email']);

        if (!$code) {
            $code = $this->passwordResetRepository->create([
                'email' => $data['email'],
                'token' => rand(100000, 999999),
            ]);
        }

        Mail::to($code->email)->send(new ResetPassword([
            'code' => $code->token,
        ]));

        return SuccessResource::make([
            'message' => 'Password reset code sent'
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request): SuccessResource|ErrorResource
    {
        $data = $request->validated();
        $code = $this->passwordResetRepository->findBy($data['email'], $data['code']);

        if (!$code) {
            return ErrorResource::make([
                'message' => trans('passwords.token')
            ]);
        }

        $user = $this->userRepository->getByEmail($data['email']);

        $this->userRepository->update($user->id, [
            'password' => Hash::make($data['password'])
        ]);

        $this->passwordResetRepository->delete($code->id);

        return SuccessResource::make([
            'message' => trans('passwords.reset')
        ]);
    }
}
