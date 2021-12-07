<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Service\ActivityService;

class ActivityController extends AbstractController
{
    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    /**
     * @Route("/api/crm/create_activity", methods={"POST"}, name="create_activity")
     */
    public function createActivity(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $response = $this->activityService->createActivity($content);
        if (array_key_exists('error', $response)) {
            return $this->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->json($response,  JsonResponse::HTTP_OK);
    }

}