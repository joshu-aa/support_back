<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use App\Service\ActivityService;

class ActivityController extends AbstractController
{
    public function __construct(ActivityService $activityService, ParameterBagInterface $parameterBag)
    {
        $this->activityService = $activityService;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @Route("/api/crm/create_activity", methods={"POST"}, name="create_activity")
     */
    public function createActivity(Request $request)
    {
        $content = $request->request->all();
        $image = $request->files->get('image');
        $response = $this->activityService->createActivity($content, $image);

        if (array_key_exists('error', $response)) {
            return $this->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->json($response,  JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/api/crm/get_activity", methods={"POST"}, name="get_activity")
     */
    public function getActivity(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $response = $this->activityService->getActivity($content);
        if (array_key_exists('error', $response)) {
            return $this->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->json($response,  JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/api/crm/image/{imageName}", methods={"GET"}, name="get-image")
     */
    public function getImage($imageName)
    {
        try {
            $file = $this->parameterBag->get('kernel.project_dir').'/public/images/efs/'.$imageName;
            $response = new BinaryFileResponse($file);
            return $response;
        } catch (\Exception $e) {
            return $this->json(['error' => 'File not found'], JsonResponse::HTTP_NOT_FOUND);
        }
    }
}