<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
/**
 * @ORM\Entity(repositoryClass=ParticipationRepository::class)
 */
class Participation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
 * @ORM\Column(type="string", length=200, nullable=true)
 */
private $codeQR;


    
/**
 * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
 * @Gedmo\Timestampable(on="update")
 */
    private $dateParticipation;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class)
     * @ORM\JoinColumn(nullable=false, name="idEvent", referencedColumnName="idEvent")
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity=User2::class)
     * @ORM\JoinColumn(nullable=false, name="idUser", referencedColumnName="iduser")
     */
    private $user;


 // ... (autres propriétés)

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $paymentStatus;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeQR(): ?string
    {
        return $this->codeQR;
    }

    public function setCodeQR(string $codeQR): self
    {
        $this->codeQR = $codeQR;

        return $this;
    }

    public function getDateParticipation(): ?\DateTimeInterface
    {
        return $this->dateParticipation;
    }

    public function setDateParticipation(): void
    {
        $this->dateParticipation = new \DateTime();
    }


    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getUser2(): ?User2
{
    return $this->user;
}

public function setUser2(?User2 $user): self
{
    $this->user = $user;

    return $this;
}



public function getPaymentStatus(): ?bool
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(bool $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }
















}
