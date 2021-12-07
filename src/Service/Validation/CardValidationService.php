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
        $location       = str_replace(' ', '', $data["location"]);
        $createdBy      = str_replace(' ', '', $data["createdBy"]);
        $unitNumber     = str_replace(' ', '', $data["unitNumber"]);

        $input = [
                    'title'         => $title, 
                    'subscriberId'  => $subscriberId, 
                    'location'      => $location, 
                    'createdBy'     => $createdBy, 
                    'unitNumber'    => $unitNumber
                ];

        $constraints = new Assert\Collection([
            'title'         => [new Assert\NotBlank(["message"  => 'Title must not be blank']), 
                                new Assert\NotNull(["message"   => 'Title must not be blank'])],

            'subscriberId'  => [new Assert\Length(["min" => 10, "max" => 10, "exactMessage" => "Subscriber ID should have exactly 10 characters"]),
                                new Assert\NotBlank(["message"  => 'Subscriber ID must not be blank']), 
                                new Assert\NotNull(["message"   => 'Subscriber ID must not be blank'])],

            'location'      => [new Assert\NotBlank(["message"  => 'Location must not be blank']), 
                                new Assert\NotNull(["message"   => 'Location must not be blank'])],

            'createdBy'     => [new Assert\NotBlank(["message"  => 'created by must not be blank']), 
                                new Assert\NotNull(["message"   => 'created by must not be blank'])],

            'unitNumber'    => [new Assert\NotBlank(["message"  => 'Unit number must not be blank']), 
                                new Assert\NotNull(["message"   => 'Unit number must not be blank'])],
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