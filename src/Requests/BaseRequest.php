<?php

namespace App\Requests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseRequest
{
    private ConstraintViolationListInterface $errors;

    public function __construct(protected ValidatorInterface $validator)
    {
        $this->populate();
    }

    public function validate(): bool
    {
        $this->errors = $this->validator->validate($this);
        if ($this->errors->count() > 0) {
            return false;
        }

        return true;
    }

    public function getRequest(): Request
    {
        return Request::createFromGlobals();
    }

    public function prepareResponse(): array
    {
        $errors = [];
        foreach ($this->errors as $message) {
            $errors[] = [
                'property' => $message->getPropertyPath(),
                'value' => $message->getInvalidValue(),
                'message' => $message->getMessage(),
            ];
        }


        return $errors;
    }

    protected function populate(): void
    {
        foreach ($this->getRequest()->toArray() as $property => $value) {
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }
}