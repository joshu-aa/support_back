<?php

namespace App\Service;

use App\Repository\ActivityRepository;
use App\Entity\Activity;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\CardService;

use App\Service\Validation\ActivityValidationService;

class ActivityService
{

    private $em;
    private $activityValidationService;

    public function __construct(EntityManagerInterface $em, 
                                ActivityValidationService $activityValidationService, 
                                ActivityRepository $activityRepository, 
                                CardService $cardService)
    {
        $this->em = $em;
        $this->activityValidationService = $activityValidationService;
        $this->activityRepository = $activityRepository;
        $this->cardService = $cardService;
    }

    public function createActivity($data) 
    {
        //validation of request
        $validation = $this->activityValidationService->validateCreateActivity($data);
        if( array_key_exists("error", $validation)) {
            return $validation;
        }

        $dateResolved = null;
        if (!is_null($data["dateResolved"])) {
            $dateResolved =  new \DateTime($data["dateResolved"]);
        }

        $activity = new Activity();
        $activity->setCardId($data["cardId"]); 
        $activity->setDateResolved($dateResolved);
        $activity->setRemarks($data["remarks"]);
        $activity->setCreatedBy($data["createdBy"]);
        $activity->setStaffId($data["staffId"]);
        $activity->setTimestamp();
        $this->em->persist($activity); 

        try {
            $this->em->flush();
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }

        if (!is_null($data["dateResolved"])) {
            $this->cardService->closingTicket($data["cardId"], $dateResolved);
        }
        
        return ["result" => "Activity created"];
    }

    public function getActivity($data) 
    {
        $activities = $this->activityRepository->getActivities($data);
        return $activities;
    }
}