<?php

declare(strict_types=1);

namespace App\User\Controller\Request;

use Symfony\Component\DependencyInjection\Attribute\Exclude;
use Symfony\Component\Validator\Constraints as Assert;

#[Exclude]
final readonly class UserRegistrationRequest
{
    #[Assert\NotBlank(message: 'user.registration.name.not_blank')]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'user.registration.name.min_length',
        maxMessage: 'user.registration.name.max_length',
    )]
    public string $name;

    #[Assert\NotBlank(message: 'user.registration.email.not_blank')]
    #[Assert\Email(message: 'user.registration.email.invalid')]
    public string $email;

    #[Assert\NotBlank(message: 'user.registration.phone.not_blank')]
    #[Assert\Regex(
        pattern: '/^\+?[1-9][0-9]{7,14}$/',
        message: 'user.registration.phone.invalid_format',
    )]
    public string $phone;

    #[Assert\NotBlank(message: 'user.registration.password.not_blank')]
    #[Assert\Length(
        min: 8,
        minMessage: 'user.registration.password.min_length',
    )]
    public string $password;

    #[Assert\NotBlank(message: 'user.registration.password_confirmation.not_blank')]
    #[Assert\Expression(
        'this.password === this.passwordConfirmation',
        message: 'user.registration.password_confirmation.passwords_mismatch',
    )]
    public string $passwordConfirmation;

    public function __construct(
        string $name,
        string $email,
        string $phone,
        string $password,
        string $passwordConfirmation,
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
        $this->passwordConfirmation = $passwordConfirmation;
    }
}
