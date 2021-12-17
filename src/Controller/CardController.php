<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Service\CardService;

class CardController extends AbstractController
{
    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
    }

    /**
     * @Route("/api/crm/create_ticket", methods={"POST"}, name="create_ticket")
     */
    public function createTicket(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $response = $this->cardService->createTicket($content);
        if (array_key_exists('error', $response)) {
            return $this->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->json($response,  JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/api/crm/get_ticket", methods={"POST"}, name="get_ticket")
     */
    public function getTicket(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $response = $this->cardService->getTicket($content);
        if (array_key_exists('error', $response)) {
            return $this->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->json($response,  JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/api/crm/search_ticket", methods={"POST"}, name="search_ticket")
     */
    public function searchTicket(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $response = $this->cardService->searchTicket($content);
        if (array_key_exists('error', $response)) {
            return $this->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->json($response,  JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/api/crm/transfer_ticket", methods={"POST"}, name="transfer_ticket")
     */
    public function transferTicket(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $response = $this->cardService->transferTicket($content);
        if (array_key_exists('error', $response)) {
            return $this->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->json($response,  JsonResponse::HTTP_OK);
    }
}