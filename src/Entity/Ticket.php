<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\TicketRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 */
class Ticket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column
     */
    private ?int $id = null;

    /**
     * @ORM\Column
     * @Assert\Positive
     */
    private ?float $prix = null;

    /**
     * @ORM\Column(length=255)
     * @Assert\Length(min=5, max=255, minMessage="Le lieu de départ doit contenir au moins 5 caractères.")
     */
    private ?string $lieuDepart = null;

    /**
     * @ORM\Column(length=255)
     * @Assert\Length(min=5, max=255, minMessage="Le lieu d'arrivée doit contenir au moins 5 caractères.")
     */
    private ?string $lieuArrive = null;

    /**
     * @ORM\Column(type=Types::DATETIME_MUTABLE)
     */
    private ?\DateTimeInterface $dateTicket = null;

    /**
     * @ORM\Column(length=255)
     */
    private ?string $statutTicket = null;

    /**
     * @ORM\ManyToOne(inversedBy="tickets")
     *
     */
    private ?Transport $idTransport = null;

    /**
     * @ORM\ManyToOne(targetEntity="User2", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, name="idUser", referencedColumnName="iduser")
     */
    private ?User2 $iduser = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $rating = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $userRating = null;

    // ... autres méthodes

    public function getUserRating(): ?int
    {
        return $this->userRating;
    }

    public function setUserRating(?int $userRating): self
    {
        $this->userRating = $userRating;

        return $this;
    }

    // ... autres méthodes

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getLieuDepart(): ?string
    {
        return $this->lieuDepart;
    }

    public function setLieuDepart(string $lieuDepart): self
    {
        $this->lieuDepart = $lieuDepart;

        return $this;
    }

    public function getLieuArrive(): ?string
    {
        return $this->lieuArrive;
    }

    public function setLieuArrive(string $lieuArrive): self
    {
        $this->lieuArrive = $lieuArrive;

        return $this;
    }

    public function getDateTicket(): ?\DateTimeInterface
    {
        return $this->dateTicket;
    }

    public function setDateTicket(\DateTimeInterface $dateTicket): self
    {
        $this->dateTicket = $dateTicket;

        return $this;
    }

    public function getStatutTicket(): ?string
    {
        return $this->statutTicket;
    }

    public function setStatutTicket(string $statutTicket): self
    {
        $this->statutTicket = $statutTicket;

        return $this;
    }

    public function getIdTransport(): ?Transport
    {
        return $this->idTransport;
    }

    public function setIdTransport(?Transport $idTransport): self
    {
        $this->idTransport = $idTransport;

        return $this;
    }

    public function getIduser(): ?User2
    {
        return $this->iduser;
    }

    public function setIduser(?User2 $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }
}