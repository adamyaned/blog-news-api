<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangeEmailConfirmRequest;
use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\SuccessResource;
use App\Mail\ChangeEmail;
use App\Repositories\Interfaces\PasswordResetRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\UserService;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UserController extends Controller
{
    public function __construct(
        private UserRepositoryInterface          $userRepository,
        private PasswordResetRepositoryInterface $passwordResetRepository,
    )
    {
    }

    public function changeEmail(ChangeEmailRequest $request): SuccessResource
    {
        $data = $request->validated();

        $code = $this->passwordResetRepository->findByEmail($data['email']);

        if (!$code) {
            $code = $this->passwordResetRepository->create([
                'email' => $data['email'],
                'token' => rand(100000, 999999),
            ]);
        }

        Mail::to($data['email'])->send(new ChangeEmail([
            'code' => $code->token,
        ]));

        return SuccessResource::make([
            'message' => 'Code for change email successfully sent'
        ]);
    }

    public function changeEmailConfirm(ChangeEmailConfirmRequest $request): SuccessResource
    {
        $data = $request->validated();
        $code = $this->passwordResetRepository->findBy($data['email'], $data['code']);

        if (!$code) {
            throw new NotFoundHttpException('Code not found');
        }

        $this->userRepository->update(auth()->id(), [
            'email' => $data['email']
        ]);

        $this->passwordResetRepository->deleteByEmail($data['email']);

        return SuccessResource::make([
            'message' => 'Email changed',
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request): SuccessResource
    {
        $data = $request->validated();

        $this->userRepository->update(auth()->id(), $data);

        return new SuccessResource([
            'message' => 'User successfully updated!',
        ]);
    }
}
