<?php

namespace App\Service\Validation;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ActivityValidationService
{
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validateCreateActivity($data)
    {
        if(is_null($data) || count($data) === 0) { 
            return ["error" => "Please make a request body"];
        }

        //replacing space to non-space
        $cardId         = str_replace(' ', '', $data["cardId"]);
        $remarks        = str_replace(' ', '', $data["remarks"]);
        $createdBy      = str_replace(' ', '', $data["createdBy"]);
        $staffId        = str_replace(' ', '', $data["staffId"]);

        $input = [
                    'cardId'        => $cardId, 
                    'createdBy'     => $createdBy, 
                    'remarks'       => $remarks,
                    'staffId'       => $staffId,
                ];

        $constraints = new Assert\Collection([
            'cardId'         => [new Assert\NotBlank(["message"    => 'Card ID must not be blank']), 
                                 new Assert\NotNull(["message"     => 'Card ID must not be blank'])],

            'remarks'       =>  [new Assert\NotBlank(["message"    => 'Remarks must not be blank']), 
                                 new Assert\NotNull(["message"     => 'Remarks must not be blank'])],
 
            'createdBy'     =>  [new Assert\NotBlank(["message"    => 'created by must not be blank']), 
                                 new Assert\NotNull(["message"     => 'created by must not be blank'])],

            'staffId'     =>  [new Assert\NotBlank(["message"      => 'Staff ID by must not be blank']), 
                                 new Assert\NotNull(["message"     => 'Staff ID by must not be blank'])],
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