<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Service\OtpService;
use App\Service\EntitySerializer;
use App\Service\API\RestClientService;

class OtpController extends AbstractController
{

    private $otpService;
    
    public function __construct(otpService $otpService, EntitySerializer $entitySerializer, RestClientService $restClientService)
    {
        $this->otpService = $otpService;
        $this->entitySerializer = $entitySerializer;
        $this->restClientService = $restClientService;
    }

    /**
     * @Route("/api/crm/otp", methods={"POST"}, name="get_otp")
     */
    public function otp(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $response = $this->otpService->generateOtp($content);
        if (array_key_exists('error', $response)) {
            return $this->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->json($response,  JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/api/crm/verify_otp", methods={"POST"}, name="verify_otp")
     */
    public function verifyOtp(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $response = $this->otpService->verifyOtp($content);
        if (array_key_exists('error', $response)) {
            return $this->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->json($response);
    }

    /**
     * @Route("/api/crm/forgot_password", methods={"POST"}, name="forgot_password")
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

    
}
