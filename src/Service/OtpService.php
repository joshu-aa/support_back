<?php

namespace App\Service;

use App\Entity\Otp;
use App\Repository\OtpRepository;
use App\Repository\UserRepository;

use App\Service\API\RestClientService;
use App\Service\CodeService;
use App\Service\ValidationService;

use Doctrine\ORM\EntityManagerInterface;

class OtpService
{
    private $codeService;
    private $otpRepository;
    private $restClientService;
    private $validationService;
     /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(OtpRepository $otpRepository, RestClientService $restClientService, UserRepository $userRepository, EntityManagerInterface $em, CodeService $codeService, ValidationService $validationService )
    {
        $this->otpRepository = $otpRepository;
        $this->userRepository = $userRepository;
        $this->restClientService = $restClientService;
        $this->codeService = $codeService;
        $this->validationService = $validationService;
        $this->em = $em;
    }

     /**
     * @param User $user
     * @return mixed
     */
    public function auth($otpAccount)
    {
        $otpAccount->setOtpCode($this->codeService->generate());
        $this->em->persist($otpAccount);
        $this->em->flush();

        return $otpAccount;
    }

    public function generateOtp($data) {
        
        $validate = $this->validationService->validateOtp($data);
        if (is_array($validate) && array_key_exists('message', $validate)) {
            return $validate;
        }

        $account = $data["account"];
        $type = $data["type"];
        $firstName = $data["firstName"];

        if(substr($account, 0, 2) === "09" && strlen($account) === 11) {
           
            $account = $this->userRepository->findOneBy([
                'contactNumber' => $account
            ]);

            if(!is_null($account)) {

                $otpAccount = $this->otpRepository->findOneBy([
                    'account' => $account->getContactNumber()
                ]);

                if (!is_null($otpAccount)) {
                    $otpAccount->setUpdatedAt();
                    $otpAccount->setOtpVerified(false);
                    $this->auth($otpAccount);
                } else {
                    $otpAccount = new Otp();
                    $otpAccount->setAccount($account->getContactNumber());
                    $otpAccount->setCreatedAt();
                    $otpAccount->setUpdatedAt();
                    $otpAccount->setOtpVerified(false);
                    $this->auth($otpAccount);
                }

                 $request = array(
                    'otp' => $otpAccount->getOtpCode(),
                    'account' => $otpAccount->getAccount(),
                    'type' => $type,
                    'firstName' => $firstName
                );

                $this->sendOtpSms($request);
                return ['account' => $request["account"]];
            } else {
                return ["message" => "Account does not exist"];
            }

        } else if (strpos($account, '@') && strpos($account, '.')) {
            $account = $this->userRepository->findOneBy([
                'email' => $account
            ]);

            if(!is_null($account)) {

                $otpAccount = $this->otpRepository->findOneBy([
                    'account' => $account->getEmail()
                ]);

                if (!is_null($otpAccount)) {
                    $otpAccount->setUpdatedAt();
                    $otpAccount->setOtpVerified(false);
                    $this->auth($otpAccount);
                } else {
                    $otpAccount = new Otp();
                    $otpAccount->setAccount($account->getEmail());
                    $otpAccount->setCreatedAt();
                    $otpAccount->setUpdatedAt();
                    $otpAccount->setOtpVerified(false);
                    $this->auth($otpAccount);
                }
                 $request = array(
                    'otp' => $otpAccount->getOtpCode(),
                    'account' => $otpAccount->getAccount(),
                    'type' => $type,
                    'firstName' => $firstName
                );
                $this->sendOtpSms($request);
                return ['account' => $request["account"]];
            } else {
                return ["message" => "Account does not exist"];
            }

        } else {
            return ["message" => "Please check your input account. Must be proper mobile number or email."];
        }
    }

      //send request to OTC
      public function sendOtpSms($dataToSend) 
      {
        $response = $this->restClientService->requestOtc("POST", "/api/otp_sms", $dataToSend);
          return ['account' => $dataToSend['account']];
      }

      public function verifyOtp($data)
    {
        $code = (int)$data["code"];
        $contactNumber = $data["account"];
        $contact = $this->otpRepository->findOneBy([
            'account' => $contactNumber,
        ]);
        if(!is_null($contact)) {
            $time = $contact->getUpdatedAt()->format('Y-m-d H:i:s');
            $otpCode = $contact->getOtpCode();
            if ($code === $otpCode && strtotime($time) > strtotime("-5 minutes")) {
                $contact->setOtpVerified(true);
                $contact->setUpdatedAt();
                $this->em->persist($contact);
                try {
                    $this->em->flush();
                } catch (\Exception $e) {
                    return ['error' => 'Error otp'];
                }

                return ['success' => 'Code Verified'];
            } else if ($code === $otpCode && strtotime($time) < strtotime("-5 minutes")) {
                return ['expired' => 'Code Expired'];
            } else {
                return ['message' => 'Invalid Code'];
            }
        } else {
            return ['error' => 'Otp account not found'];
        }
    }
}