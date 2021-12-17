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

    public function createActivity($data,$image) 
    {
        //validation of request
        $validation = $this->activityValidationService->validateCreateActivity($data);
        if( array_key_exists("error", $validation)) {
            return $validation;
        }

        $scheduledDate = null;
        if(!($data["scheduledDate"] == "null" || $data["scheduledDate"] == null)) {
            $scheduledDate = new \DateTime($data["scheduledDate"]);
        }
        $activityDate = new \DateTime($data["activityDate"]);
        $activity = new Activity();
        $activity->setCardId($data["cardId"]); 
        $activity->setActivityDate($activityDate);
        $activity->setRemarks($data["remarks"]);
        $activity->setCreatedBy($data["createdBy"]);
        $activity->setStaffId($data["staffId"]);
        $activity->setImageFile($image);
        $activity->setScheduledDate($scheduledDate);
        $activity->setTimestamp();
        $this->em->persist($activity); 

        try {
            $this->em->flush();
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }

        if ($data["isClose"] == "true") {
            $this->cardService->closingTicket($data["cardId"], $activityDate);
        }

        if ($data["action"] == "forward") {
            $this->cardService->transferTicket($data);
        }
        
        //if the ticket is scheduled for somthing
        if(!is_null($scheduledDate)) {
            $this->cardService->setSchedule($data["cardId"], $scheduledDate);
        }

        
        return ["result" => "Activity created"];
    }

    public function getActivity($data) 
    {
        $activities = $this->activityRepository->getActivities($data);
        return $activities;
    }
}