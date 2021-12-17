<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\EntitySerializer;
use App\Service\API\RestClientService;
use App\Service\RoleService;

class RoleController extends AbstractController
{
    public function __construct( RoleService $roleService, EntitySerializer $entitySerializer)
    {
        $this->roleService = $roleService;
        $this->entitySerializer = $entitySerializer;
    }
    /**
     * @Route("/api/crm/get_roles", methods={"GET"}, name="get_roles")
     */
    public function getRoles(Request $request)
    {
        $response = $this->roleService->getRoles();
        if (array_key_exists('error', $response)) {
            return $this->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->json($response,  JsonResponse::HTTP_OK);
    }

    
    /**
     * @Route("/api/crm/add_roles", methods={"POST"}, name="add_roles")
     */
    public function addRoles(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $response = $this->roleService->addRole($content);
        if (array_key_exists('error', $response)) {
            return $this->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->json($response,  JsonResponse::HTTP_OK);
    }
}