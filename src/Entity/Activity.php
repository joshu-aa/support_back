<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass=ActivityRepository::class)
 * @Vich\Uploadable
 */
class Activity implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $cardId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $remarks;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $activityDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\Column(type="integer")
     */
    private $staffId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="activity_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $scheduledDate;
    

        public function jsonSerialize()
    {
        return [
            'id'            => $this->id,
            'cardId'        => $this->cardId,
            'remarks'       => $this->remarks,
            'activityDate'  => $this->activityDate,
            'createdBy'     => $this->createdBy,
            'staffId'       => $this->staffId,
            'image'         => $this->image,
            'timestamp'     => $this->timestamp->format("Y-m-d H:i:s"),
        ];
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->timestamp = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

     public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCardId(): ?int
    {
        return $this->cardId;
    }

    public function setCardId(int $cardId): self
    {
        $this->cardId = $cardId;

        return $this;
    }

    public function getRemarks(): ?string
    {
        return $this->remarks;
    }

    public function setRemarks(string $remarks): self
    {
        $this->remarks = $remarks;

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

    public function getActivityDate(): ?\DateTimeInterface
    {
        return $this->activityDate;
    }

    public function setActivityDate(?\DateTimeInterface $activityDate): self
    {
        $this->activityDate = $activityDate;

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

    public function getStaffId(): ?int
    {
        return $this->staffId;
    }

    public function setStaffId(int $staffId): self
    {
        $this->staffId = $staffId;

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
