<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

use App\Repository\TransportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransportRepository::class)
 */
class Transport
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
    private ?int $nbrePlaces = null;

    /**
     * @Assert\NotBlank
     * @ORM\Column(length=255)
     * @Assert\Choice(choices={"bus", "train", "metro", "taxi", "voiture"})
     */
    private ?string $typeTransport = null;

    /**
     * @ORM\Column(length=255)
     */
    private ?string $disponibilite = null;

    /**
     * @ORM\ManyToMany(targetEntity=User2::class, inversedBy="transports", cascade={"persist"})
     * @ORM\JoinTable(name="transport_user",
     *      joinColumns={@ORM\JoinColumn(name="transport_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="iduser")}
     * )
     */
    private Collection $iduser;

    /**
     * @ORM\OneToMany(mappedBy="idTransport", targetEntity=Ticket::class)
     */
    private Collection $tickets;

    public function __construct()
    {
        $this->iduser = new ArrayCollection();
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrePlaces(): ?int
    {
        return $this->nbrePlaces;
    }

    public function setNbrePlaces(int $nbrePlaces): self
    {
        $this->nbrePlaces = $nbrePlaces;

        return $this;
    }

    public function getTypeTransport(): ?string
    {
        return $this->typeTransport;
    }

    public function setTypeTransport(string $typeTransport): self
    {
        $this->typeTransport = $typeTransport;

        return $this;
    }

    public function getDisponibilite(): ?string
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(string $disponibilite): self
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    /**
     * @return Collection<int, User2>
     */
    public function getIduser(): Collection
    {
        return $this->iduser;
    }

    public function addIduser(User2 $iduser): self
    {
        if (!$this->iduser->contains($iduser)) {
            $this->iduser->add($iduser);
        }

        return $this;
    }

    public function removeIduser(User2 $iduser): self
    {
        $this->iduser->removeElement($iduser);

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setIdTransport($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getIdTransport() === $this) {
                $ticket->setIdTransport(null);
            }
        }

        return $this;
    }
}