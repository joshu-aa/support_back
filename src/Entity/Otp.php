<?php

namespace App\Entity;

use App\Repository\OtpRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OtpRepository::class)
 */
class Otp
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
    private $account;

    /**
     * @ORM\Column(type="integer")
     */
    private $otpCode;

    /**
     * @ORM\Column(type="boolean")
     */
    private $otpVerified;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccount(): ?string
    {
        return $this->account;
    }

    public function setAccount(string $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getOtpCode(): ?int
    {
        return $this->otpCode;
    }

    public function setOtpCode(int $otpCode): self
    {
        $this->otpCode = $otpCode;

        return $this;
    }

    public function getOtpVerified(): ?bool
    {
        return $this->otpVerified;
    }

    public function setOtpVerified(bool $otpVerified): self
    {
        $this->otpVerified = $otpVerified;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTime();

        return $this;
    }


    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }
}
