<?php

namespace App\UI\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class LoginData
{
    #[Assert\NotBlank(message: "Email cannot be empty.")]
    #[Assert\Email(message: "Invalid email address.")]
    public ?string $email = null;

    #[Assert\NotBlank(message: "Password cannot be empty.")]
    public ?string $password = null;
}