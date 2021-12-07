<?php

namespace App\Service;

use App\Repository\CardRepository;
use App\Entity\Card;
use Doctrine\ORM\EntityManagerInterface;

use App\Service\Validation\CardValidationService;

class CardService
{

    private $em;
    private $cardValidationService;

    public function __construct(EntityManagerInterface $em, CardValidationService $cardValidationService)
    {
        $this->em = $em;
        $this->cardValidationService = $cardValidationService;
    }

    public function createTicket($data) 
    {
        //validation of request
        $validation = $this->cardValidationService->validateCreateTicket($data);
        if( array_key_exists("error", $validation)) {
            return $validation;
        }

        $card = new Card();
        $card->setTitle($data["title"]);
        $card->setDescription($data["description"]);
        $card->setSubscriberId($data["subscriberId"]);
        $card->setLocation($data["location"]);
        $card->setTowerName($data["towerName"]);
        $card->setUnitNumber($data["unitNumber"]);
        $card->setTicketStatus("pending");
        $card->setAssignedGroup($data["assignedGroup"]);
        $card->setCreatedBy($data["createdBy"]);
        $card->setTimestamp();
        $this->em->persist($card);
        try {
            $this->em->flush();
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }

        return ["result" => "Adding new ticket successful"];
    }

    public function  getTicket($data) 
    {
        
    }
}