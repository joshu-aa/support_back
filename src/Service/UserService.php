<?php

namespace App\Service;

use App\Entity\User;
// use App\Entity\UserAction;
use App\Repository\UserRepository;
// use App\Repository\OtpRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;

use App\Service\ValidationService;

class UserService
{

/**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserRepository
     */
    private $userRepository;

    // /**
    //  * @var OtpRepository
    //  */
    // private $otpRepository;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var LoggerInterface
     */
    private $log;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository, Security $security, UserPasswordEncoderInterface $passwordEncoder, LoggerInterface $log, ValidationService $validationService)
    {
        $this->em = $em;
        $this->validationService = $validationService;
        $this->userRepository = $userRepository;
        $this->security = $security;
        $this->passwordEncoder = $passwordEncoder;
        $this->log = $log;
    }

    public function userRegister($data) 
    {
        $validate = $this->validationService->validateRegister($data);
        if (is_array($validate) && array_key_exists('error', $validate)) return $validate;

        $emailAddress = $data['email'];
        $password = $data['password'];
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];

    //inserting registrant
      try {
        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($emailAddress);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        $user->setCreatedAt();
        $user->setUpdatedAt();
        $this->em->persist($user);
        $this->em->flush();
      } catch (\Exception $e) {
          return ['error' => 'Server error. Try again later.'];
      }

      return $user;
    }

    // public function forgotPassword($data)
    // {
    //     $account = $data["account"];
    //     $password = $data["password"];

    //     $validate = $this->validationService->validatePassword($password);
    //     if (is_array($validate) && array_key_exists('error', $validate)) return $validate;
    //     $otp = $this->otpRepository->findOneBy(['account' => $account]);
    //     if (!is_null($otp) && $otp->getOtpVerified()) {
    //         $time = $otp->getUpdatedAt()->format('Y-m-d H:i:s');
    //         if (strtotime($time) > strtotime("-5 minutes")) {
    //             if (strpos($account, '@') && strpos($account, '.')) {
    //                 $user = $this->userRepository->findOneBy([
    //                     'email' => $account
    //                 ]);
    //             } else if (substr($account, 0, 2) === '09' && strlen($account) === 11){
    //                 $user = $this->userRepository->findOneBy([
    //                     'contactNumber' => $account
    //                 ]);
    //             } else {
    //                 return ['error' => "Invalid account"];
    //             }
    //             $user->setPassword(
    //                 $this->passwordEncoder->encodePassword($user, $password)
    //             );
    //             $user->setUpdatedAt();
    //             $this->em->persist($user);
    //             try {
    //                 $this->em->flush();
    //             } catch (\Exception $e) {
    //                 return ['error' => 'Error changing password'];
    //             }
        
    //             $action = new UserAction();
    //             $action->setAgentId($user->getAgentId());
    //             $action->setAction("Password changed");
    //             $action->setTimestamp();
    //             $this->em->persist($action);
    //             $this->em->flush();

    //             $otp->setOtpVerified(false);
    //             $otp->setUpdatedAt();
    //             $this->em->persist($otp);
    //             try {
    //                 $this->em->flush();
    //             } catch (\Exception $e) {
    //                 return ['error' => 'Error otp'];
    //             }

    //             return ['success' => 'Password updated'];
    //         } else if (strtotime($time) < strtotime("-5 minutes")) {
    //             return ['error' => 'Invalid request'];
    //         }
    //     } else if (is_null($otp) || !$otp->getOtpVerified()) {
    //         return ['error' => 'Invalid request'];
    //     }
    // }

    public function changePassword($data, $user, UserPasswordEncoderInterface $passwordEncoder)
    {
        $oldPassword = $data["oldPassword"];
        $newPasswordConfirm = $data["newPasswordConfirm"];

        $validate = $this->validationService->validatePassword($newPasswordConfirm);
        if (is_array($validate) && array_key_exists('error', $validate)) return $validate;
        
        $checkPass = $passwordEncoder->isPasswordValid($user, $oldPassword);

        if ($checkPass === true) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $newPasswordConfirm));
            $user->setUpdatedAt();
            $this->em->persist($user);
            try {
                $this->em->flush();
            } catch (\Exception $e) {
                return ['error' => 'Error changing password'];
            }

            $action = new UserAction();
            $action->setAgentId($user->getAgentId());
            $action->setAction("Password changed");
            $action->setTimestamp();
            $this->em->persist($action);
            $this->em->flush();
            return ['success' => 'Password changed'];
        } else {
            return ['error' => 'Incorrect password'];
        }
    }
}
