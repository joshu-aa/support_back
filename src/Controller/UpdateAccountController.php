<?php

namespace App\Controller;

use App\Service\UserService;
use App\Service\OtpService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\EntitySerializer;
use App\Service\API\RestClientService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UpdateAccountController extends AbstractController
{

    private $userService;
    
    public function __construct(otpService $otpService, UserService $userService, EntitySerializer $entitySerializer, RestClientService $restClientService)
    {
        $this->userService = $userService;
        $this->entitySerializer = $entitySerializer;
        $this->restClientService = $restClientService;
        $this->otpService = $otpService;
    }

    /**
     * @Route("/api/account/forgot_password", methods={"POST"}, name="forgot_password")
     */
    public function forgotPassword(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $response = $this->userService->forgotPassword($content);

        if (array_key_exists('error', $response)) {
            return $this->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->json($response);
    }
    
    /**
     * @Route("/api/change_password", name="change_password", methods={"POST"})
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $content = json_decode($request->getContent(), true);
        $user = $this->getUser();
        $user = $this->userService->changePassword($content, $user, $passwordEncoder);

        if (array_key_exists('error', $user)) {
            return $this->json($user, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->json($user);
    }

}
