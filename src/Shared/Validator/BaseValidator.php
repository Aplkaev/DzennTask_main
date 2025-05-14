<?php

declare(strict_types=1);

namespace App\Shared\Validator;

use App\Shared\Response\ApiResponse;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseValidator
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public function validate(object $obj): void
    {
        $errors = $this->validator->validate($obj);

        $messages = ['message' => 'validation_failed', 'errors' => []];

        /** @var ConstraintViolation $message */
        foreach ($errors as $message) {
            $messages['errors'][] = [
                'property' => $message->getPropertyPath(),
                'value' => $message->getInvalidValue(),
                'message' => $message->getMessage(),
            ];
        }

        if (count($messages['errors']) > 0) {
            $response = ApiResponse::errors($messages);
            $response->send();

            exit;
        }
    }
}
