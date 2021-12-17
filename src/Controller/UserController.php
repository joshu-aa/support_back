<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\EntitySerializer;
use App\Service\API\RestClientService;
use App\Service\UserService;

class UserController extends AbstractController
{
    public function __construct( UserService $userService, EntitySerializer $entitySerializer)
    {
        $this->userService = $userService;
        $this->entitySerializer = $entitySerializer;
    }

    /**
     * @Route("/api/user", name="user", methods={"GET"})
     */
    public function getAccount()
    {
        $account = $this->getUser();
        $data = $this->entitySerializer->serializeEntity($account, [
            "staffId",
            "contactNumber", 
            "firstName", 
            "lastName",
            "email",
            "userGroup",
            "roles"
        ]);
        return new JsonResponse($data);
    }


    /**
     * @Route("api/crm/register", methods={"POST"}, name="user_register")
     */
    public function userRegister(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $response = $this->userService->userRegister($content);

        if (array_key_exists('error', $response)) {
            return $this->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->json($response);
    }

    
    /**
     * @Route("api/crm/approve_registrant", methods={"POST"}, name="approved_registrant")
     */
    public function approveRegistrant(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $response = $this->userService->approveRegistrant($content);

        if (array_key_exists('error', $response)) {
            return $this->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->json($response);
    }
}