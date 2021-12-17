<?php

namespace App\Service\Validation;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CardValidationService
{
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validateCreateTicket($data)
    {
        if(is_null($data) || count($data) === 0) { 
            return ["error" => "Please make a request body"];
        }

        //replacing space to non-space
        $title          = str_replace(' ', '', $data["title"]);
        $subscriberId   = str_replace(' ', '', $data["subscriberId"]);
        $createdBy      = str_replace(' ', '', $data["createdBy"]);
        $staffId        = str_replace(' ', '', $data["staffId"]);

        $input = [
                    'title'         => $title, 
                    'subscriberId'  => $subscriberId, 
                    'createdBy'     => $createdBy, 
                    'staffId'       => $staffId,
                ];

        $constraints = new Assert\Collection([
            'title'         => [new Assert\NotBlank(["message"  => 'Title must not be blank']), 
                                new Assert\NotNull(["message"   => 'Title must not be blank'])],

            'subscriberId'  => [new Assert\Length(["min" => 10, "max" => 10, "exactMessage" => "Subscriber ID should have exactly 10 characters"]),
                                new Assert\NotBlank(["message"  => 'Subscriber ID must not be blank']), 
                                new Assert\NotNull(["message"   => 'Subscriber ID must not be blank'])],

            'createdBy'     => [new Assert\NotBlank(["message"  => 'created by must not be blank']), 
                                new Assert\NotNull(["message"   => 'created by must not be blank'])],

            'staffId'       => [new Assert\NotBlank(["message"  => 'Staff ID must not be blank']), 
                                new Assert\NotNull(["message"   => 'Staff ID must not be blank'])],
        ]);

        $violations = $this->validator->validate($input, $constraints);
        $errorMessage = "";

        if (count($violations) > 0) {
            for ($i = 0; $i <= count($violations) -1; $i++) {
                if($i==0) {
                    $errorMessage = $violations[$i]->getMessage();
                } else {
                    $errorMessage = $errorMessage.", ". $violations[$i]->getMessage();
                }
            }
            return ['error' => $errorMessage];
        } else {
            //if validation success 
            return ['success' => 'valid input'];
        }
    }
}