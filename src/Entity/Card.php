<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
/**
 * @ORM\Entity(repositoryClass=CardRepository::class)
 */
class Card implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ticketStatus;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $assignedGroup;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateResolved;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $qualityControlFlag;

    /**
     * @ORM\Column(type="integer")
     */
    private $subscriberId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $unitNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $towerName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="integer")
     */
    private $staffId;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $referenceNumber;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $scheduledDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTicketStatus(): ?string
    {
        return $this->ticketStatus;
    }

    public function setTicketStatus(string $ticketStatus): self
    {
        $this->ticketStatus = $ticketStatus;

        return $this;
    }

    public function getAssignedGroup(): ?string
    {
        return $this->assignedGroup;
    }

    public function setAssignedGroup(string $assignedGroup): self
    {
        $this->assignedGroup = $assignedGroup;

        return $this;
    }

    public function getDateResolved(): ?\DateTimeInterface
    {
        return $this->dateResolved;
    }

    public function setDateResolved(?\DateTimeInterface $dateResolved): self
    {
        $this->dateResolved = $dateResolved;

        return $this;
    }

    public function getQualityControlFlag(): ?bool
    {
        return $this->qualityControlFlag;
    }

    public function setQualityControlFlag(?bool $qualityControlFlag): self
    {
        $this->qualityControlFlag = $qualityControlFlag;

        return $this;
    }

    public function getSubscriberId(): ?int
    {
        return $this->subscriberId;
    }

    public function setSubscriberId(int $subscriberId): self
    {
        $this->subscriberId = $subscriberId;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getUnitNumber(): ?string
    {
        return $this->unitNumber;
    }

    public function setUnitNumber(string $unitNumber): self
    {
        $this->unitNumber = $unitNumber;

        return $this;
    }

    public function getTowerName(): ?string
    {
        return $this->towerName;
    }

    public function setTowerName(?string $towerName): self
    {
        $this->towerName = $towerName;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(): self
    {
        $this->timestamp = new \DateTime();

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(string $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function jsonSerialize()
    {
        $jsonSerialize = [
            'id'                => $this->id,
            'title'             => $this->title,
            'description'       => $this->description,
            'ticketStatus'      => $this->ticketStatus,
            'assignedGroup'     => $this->assignedGroup,
            'dateResolved'      => null,
            'qualityControlFlag'=> $this->qualityControlFlag,
            'subscriberId'      => $this->subscriberId,
            'location'          => $this->location,
            'unitNumber'        => $this->unitNumber,
            'towerName'         => $this->towerName,
            'createdBy'         => $this->createdBy,
            'staffId'           => $this->staffId,
            'referenceNumber'   => $this->referenceNumber,
            'timestamp'         => $this->timestamp->format("Y-m-d H:i:s"),
            "scheduledDate"     => null
        ];

        if (!is_null($this->dateResolved)) {
            $jsonSerialize["dateResolved"] = $this->dateResolved->format("Y-m-d H:i:s");
        } else {
            $jsonSerialize["dateResolved"] = null;
        }

        if (!is_null($this->scheduledDate)) {
            $jsonSerialize["scheduledDate"] = $this->scheduledDate->format("Y-m-d H:i:s");
        } else {
            $jsonSerialize["scheduledDate"] = null;
        }

        
        return $jsonSerialize;
    }

    public function getStaffId(): ?int
    {
        return $this->staffId;
    }

    public function setStaffId(int $staffId): self
    {
        $this->staffId = $staffId;

        return $this;
    }

    public function getReferenceNumber(): ?int
    {
        return $this->referenceNumber;
    }

    public function setReferenceNumber(int $referenceNumber): self
    {
        $this->referenceNumber = $referenceNumber;

        return $this;
    }

    public function getScheduledDate(): ?\DateTimeInterface
    {
        return $this->scheduledDate;
    }

    public function setScheduledDate(?\DateTimeInterface $scheduledDate): self
    {
        $this->scheduledDate = $scheduledDate;

        return $this;
    }
}
