<?php

namespace App\Service;

use App\Repository\CardRepository;
use App\Entity\Card;
use Doctrine\ORM\EntityManagerInterface;

use App\Service\Validation\CardValidationService;

class CardService
{

    private $em;
    private $cardRepository;

    public function __construct(EntityManagerInterface $em, CardValidationService $cardValidationService, CardRepository $cardRepository)
    {
        $this->em = $em;
        $this->cardValidationService = $cardValidationService;
        $this->cardRepository = $cardRepository;
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
        $card->setStaffId($data["staffId"]);
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
}