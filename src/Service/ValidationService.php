<?php

namespace App\Service;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository,ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function validateRegister ($data)
    {
        if (empty($data['password']) || empty($data['firstName']) || empty($data['lastName'])) return ['error' => 'A required field is missing.'];

        if (empty($data['email'])) return ['error' => 'Please enter your email'];

        if (strlen($data['password']) < 6) return ['error' => 'Password must be at least 6 characters long.'];

        if (!is_null($data['email'])) {
            $email = $this->userRepository->findOneBy(['email' => $data['email']]);
            if ($email !== null) { 
                return ['error' => 'email is already existing'];
            }
        }
    }

    public function validatePassword($password)
    {
        if (strlen($password) < 6) return ['error' => 'Password must be at least 6 characters long.'];
    }

    public function validateOtp($data) 
    {
        if (empty($data['account'])) return ['message' => 'Account is required.']; 
        
        if (empty($data['type'])) return ['message' => 'Type is required.'];

        if (!is_string($data['type']) || strlen($data['type']) > 1) return ['message' => 'Invalid data for type'];
    }

    public function validateApproveRegistrant ($data)
    {
        if (empty($data['password']) || empty($data['firstName']) || empty($data['lastName'])) return ['error' => 'A required field is missing.'];

        if (empty($data['otcGroup'])) return ['error' => 'Please enter otc group'];

        if (empty($data['contactNumber'])) return ['error' => 'Please enter your contactNumber'];

        if (empty($data['email'])) return ['error' => 'Please enter your email'];

        if (empty($data['roles'])) return ['error' => 'Please enter agent\'s role'];

        if (strlen($data['contactNumber']) !== 11) return ['error' => 'contact number must be 11 characters long.'];
        
        if (strlen($data['password']) < 6) return ['error' => 'Password must be at least 6 characters long.'];

        if (!is_null($data['contactNumber'])) {
            $contact = $this->userRepository->findOneBy(['contactNumber' => $data['contactNumber']]);
            if ($contact !== null) { 
                return ['error' => 'contact number is already existing'];
            }
        }

        if (!is_null($data['email'])) {
            $email = $this->userRepository->findOneBy(['email' => $data['email']]);
            if ($email !== null) { 
                return ['error' => 'email is already existing'];
            }
        }
    }

    public function validateSms($data) 
    {
        // dd($data);

        if (empty($data['body'])) return ['error' => 'Please put some message'];

        if (empty($data['sendto'])) return ['error' => 'Please enter contactNumber'];

        if (strlen($data['sendto']) !== 13) return ['error' => 'contact number must be 13 characters long.'];
        
        if(substr($data['sendto'], 0, -10) !== "+63")  return ['error' => 'Please use +63 format in Phone number (eg. +639123654789)'];
        
        return ['success' => 'sending sms success'];

    }
}