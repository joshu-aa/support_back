<?php

namespace App\Service;

use App\Repository\CardRepository;
use App\Entity\Card;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\HTTPClientService;

use App\Service\Validation\CardValidationService;

class CardService
{

    private $em;
    private $cardRepository;

    public function __construct(EntityManagerInterface $em, 
                                CardValidationService $cardValidationService, 
                                CardRepository $cardRepository,
                                HTTPClientService $httpClientService)
    {
        $this->em = $em;
        $this->cardValidationService = $cardValidationService;
        $this->cardRepository = $cardRepository;
        $this->httpClientService = $httpClientService;
        $this->accountsUrl = $_ENV["ACCOUNTS_URL"];
        $this->accountsHeader = [
            "headers" => [
                "Token" => $_ENV["ACCOUNTS_TOKEN"],
                "Identity" => $_ENV["ACCOUNTS_ID"]
            ]
        ];
    }

    public function createTicket($data) 
    {
        //validation of request
        $validation = $this->cardValidationService->validateCreateTicket($data);
        if( array_key_exists("error", $validation)) {
            return $validation;
        }

        $accountsHeader = $this->accountsHeader;
        $accountsHeader["json"] = [
            "subscriberId" => $data["subscriberId"]
        ];

        $subscriberDetails = $this->httpClientService->getDataFromEndPoint("POST", $this->accountsUrl."/api/account/getSubscriberDetails", $accountsHeader);
        if (array_key_exists("error", $subscriberDetails)){ 
            return $subscriberDetails;
        }   else if(array_key_exists("failed", $subscriberDetails)) {
            return ["error" => $subscriberDetails["failed"]];
        }

        $towerName = null;

        if ($subscriberDetails["towerName"] == "N/A" || $subscriberDetails["towerName"] == null){
             $towerName = null;
        } else {
             $towerName =  $subscriberDetails["towerName"];
        }
        $referenceNumber = $this->referenceNumberGenerator();

        $card = new Card();
        $card->setTitle($data["title"]);
        $card->setDescription($data["description"]);
        $card->setSubscriberId($data["subscriberId"]);
        $card->setLocation($subscriberDetails["location"]);
        $card->setTowerName($towerName);
        $card->setUnitNumber($subscriberDetails["unitNumber"]);
        $card->setTicketStatus("pending");
        $card->setAssignedGroup($data["assignedGroup"]);
        $card->setCreatedBy($data["createdBy"]);
        $card->setStaffId($data["staffId"]);
        $card->setReferenceNumber($referenceNumber);
        $card->setTimestamp();
        $this->em->persist($card);
        try {
            $this->em->flush();
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }

        return $card;
    }

    public function  getTicket($data) 
    {
        $cards = $this->cardRepository->getCards($data);
        return $cards;
    }

    public function closingTicket($cardId, $dateResolved)
    {
        $card = $this->cardRepository->findOneBy(["id" => $cardId]);
        $card->setDateResolved($dateResolved);
        $card->setTicketStatus("resolved");
        $this->em->persist($card);
        $this->em->flush();
    }

    public function searchTicket($data)
    {
        try {
            $card = $this->cardRepository->searchTickets($data);
        } catch (\Throwable $th) {
            return ["error" => "getting tickets failed"];
        }
        return $card;
    }

    public function transferTicket($data)
    {
        $card = $this->cardRepository->findOneBy(["id" => $data["cardId"]]);
        $card->setAssignedGroup($data["assignedGroup"]);
        $this->em->persist($card);
        try {
            $this->em->flush();
        } catch (\Throwable $th) {
            return ["error" => $th->getMessage()];
        }
        return ["result" => "card transfer success"];
    }

    public function referenceNumberGenerator() 
    {
        $date = new \DateTime();
        $date = $date->format("ymd");
        
        $latestReferenceNumber = $this->cardRepository->getLatestReferenceNumber();

        $referenceNumber = null;
        if(count($latestReferenceNumber) === 0) {
            $referenceNumber = intval($date . "00001");
        } else {
            $latestReferenceNumber = $latestReferenceNumber[0]["referenceNumber"];
            $latestReferenceNumber = (int)$latestReferenceNumber;
            $referenceNumber = $latestReferenceNumber + 1;
        }

        return $referenceNumber;
    }

    public function setSchedule($cardId, $sheduledDate)
    {
        $card = $this->cardRepository->findOneBy(["id" => $cardId]);
        $card->setScheduledDate($sheduledDate);
        $this->em->persist($card);
        $this->em->flush();
    }
}