<?php

namespace App\Tests\Form;

use App\UI\DTO\LoginData;
use App\UI\Form\LoginFormType;
use Symfony\Component\Form\Test\TypeTestCase;

use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;

class LoginFormTypeTest extends TypeTestCase
{
    protected function getExtensions(): array
    {
        $validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testSubmitValidData(): void
    {
        $formData = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $form = $this->factory->create(LoginFormType::class);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertInstanceOf(LoginData::class, $form->getData());
        $this->assertEquals('test@example.com', $form->getData()->email);
        $this->assertEquals('password123', $form->getData()->password);
    }

    public function testInvalidEmail(): void
    {
        $formData = [
            'email' => '',
            'password' => 'password123',
        ];

        $form = $this->factory->create(LoginFormType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());
        $this->assertNotEmpty($form->get('email')->getErrors());
    }
}