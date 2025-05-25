<?php

declare(strict_types=1);

namespace App\User\Exception;

final class UserAlreadyExistsException extends \DomainException
{
    public static function forEmail(string $email): self
    {
        return new self(\sprintf('User with email "%s" already exists', $email));
    }

    public static function forPhone(string $phone): self
    {
        return new self(\sprintf('User with phone "%s" already exists', $phone));
    }
}
