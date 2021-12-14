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
            "roles"
        ]);
        return new JsonResponse($data);
    }
}