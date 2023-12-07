<?php

namespace App\Entity;

use App\Repository\User2Repository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;





/**
 * User2
 *
 * @ORM\Table(name="user2")
 * @ORM\Entity(repositoryClass=User2Repository::class)
 */
class User2  implements UserInterface
{
    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user2", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="user2", orphanRemoval=true)
     */
    private $posts;
    /**
     * @var int
     *
     * @ORM\Column(name="iduser", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iduser;

    /**
    
     
     * @Assert\NotBlank(message=" nom doit etre non vide")
     * 
     * @ORM\Column(type="string", length=255)
     */
    private $nomuser;

    /**
    
     
     * @Assert\NotBlank(message=" prenom doit etre non vide")
     * 
     * @ORM\Column(type="string", length=255)
     */
    /*@Assert\NotBlank(message="L'email doit être non vide")
     * @Assert\Email(message="L'email '{{ value }}' n'est pas une adresse email valide.")
     * @Assert\Callback(callback="validateMailuser")*/
    private $prenomuser;
    /**
     * @Assert\NotBlank(message="L'email doit être non vide")
     * @Assert\Email(message="L'email '{{ value }}' n'est pas une adresse email valide.")
     * @Assert\Regex(
     *     pattern="/\*\*\*@/",
     *     match=false,
     *     message="L'email ne peut pas contenir '***@**'."
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $mailuser;

        public function validateMailuser(ExecutionContextInterface $context): void
    {
        $value = $this->mailuser;

        if (strpos($value, '***@**') !== false) {
            $context->buildViolation("L'email ne peut pas contenir '***@**'.")
                ->atPath('mailuser')
                ->addViolation();
        }
    }
    
    /**
     * @Assert\NotBlank(message=" password doit etre non vide")
     * @Assert\Length(
     *      min = 8,
     *      minMessage=" Entrer un password au mini de 8 caracteres"
     *
     *     )
     * @ORM\Column(name="mdpuser", type="string", length=255, nullable=true)
     */
    private $mdpuser;

     /**
     * @Assert\NotBlank(message=" adresse doit etre non vide")
     
     * @ORM\Column(name="adressuser", type="string", length=255, nullable=true)
     */
    private $adressuser;

    /**
     * @var float
     *
     * @ORM\Column(name="walletuser", type="float", precision=10, scale=0, nullable=false, options={"default"="250"})
     */
    private $walletuser = 250;

     /**
     * @Assert\NotBlank(message=" classe doit etre non vide")
     *
     * @ORM\Column(name="classeuser", type="string", length=200, nullable=false)
     */
    private $classeuser;

     /**
     * @Assert\NotBlank(message=" role doit etre non vide")
     *
     * @ORM\Column(name="roleuser", type="string", length=200, nullable=false)
     */
    private $roleuser ;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="isBlocked", type="boolean", nullable=true)
     */
    private $isblocked = false;
    
    /**
 * @ORM\Column(type="string", length=180, nullable=true)
 */
private $reset_token;

    /**
     * @ORM\ManyToMany(targetEntity=Transport::class, mappedBy="iduser")
     */
    private Collection $transports;

    /**
     * @ORM\OneToMany(mappedBy="iduser", targetEntity=Ticket::class)
     */
    private Collection $tickets;



/**
     * @ORM\ManyToMany(targetEntity=Event::class)
     * @ORM\JoinTable(name="panier",
     *      joinColumns={@ORM\JoinColumn(name="iduser", referencedColumnName="iduser")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="idEvent")}
     * )
     */
    private $panier;









    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function getNomuser(): ?string
    {
        return $this->nomuser;
    }

    public function setNomuser(string $nomuser): static
    {
        $this->nomuser = $nomuser;

        return $this;
    }

    public function getPrenomuser(): ?string
    {
        return $this->prenomuser;
    }

    public function setPrenomuser(string $prenomuser): static
    {
        $this->prenomuser = $prenomuser;

        return $this;
    }

    public function getMailuser(): ?string
    {
        return $this->mailuser;
    }

    public function setMailuser(string $mailuser): static
    {
        $this->mailuser = $mailuser;

        return $this;
    }

    public function getMdpuser(): ?string
    {
        return $this->mdpuser;
    }

    public function setMdpuser(string $mdpuser): static
    {
        $this->mdpuser = $mdpuser;

        return $this;
    }

    public function getAdressuser(): ?string
    {
        return $this->adressuser;
    }

    public function setAdressuser(string $adressuser): static
    {
        $this->adressuser = $adressuser;

        return $this;
    }

    public function getWalletuser(): ?float
    {
        return $this->walletuser;
    }

    public function setWalletuser(float $walletuser): static
    {
        $this->walletuser = $walletuser;

        return $this;
    }

    public function getClasseuser(): ?string
    {
        return $this->classeuser;
    }

    public function setClasseuser(string $classeuser): static
    {
        $this->classeuser = $classeuser;

        return $this;
    }

    public function getRoleuser(): ?string
    {
        return $this->roleuser;
    }

    public function setRoleuser(?string $roleuser): static
    {
        $this->roleuser = $roleuser;

        return $this;
    }

    public function getIsblocked(): ?bool
    {
        return $this->isblocked;
    }

    public function setIsblocked(?bool $isblocked): static
    {
        $this->isblocked = $isblocked;

        return $this;
    }
       /**
     * @return mixed
     */
    public function getResetToken()
    {
        return $this->reset_token;
    }

    /**
     * @param mixed $reset_token
     */
    public function setResetToken($reset_token): void
    {
        $this->reset_token = $reset_token;
    }
       
    public function getRoles(): array
    {
        return [$this->roleuser ?? 'ROLE_USER'];
    }

    public function getPassword(): ?string
    {
        return $this->mdpuser;
    }

    public function getSalt(): ?string
    {
        // Vous n'avez pas besoin de sel avec le hachage de mot de passe moderne
        return null;
    }

    public function getUsername(): ?string
    {
        return $this->mailuser;
    }

    public function eraseCredentials(): void
    {
        // Si vous stockez des données temporaires et sensibles dans l'utilisateur, effacez-les ici
        // Cette méthode est appelée après que le mot de passe a été utilisé pour l'authentification
    }
    public function __toString(): string
    {
        return $this->nomuser . ' ' . $this->prenomuser;
    }


    public function getUserIdentifier(): string
    {
        return $this->getMailuser();
    }

















    public function __construct()
    {
        $this->panier = new ArrayCollection();
        $this->transports = new ArrayCollection();
        $this->tickets = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }
  /**
     * @return Collection|Event[]
     */
    public function getPanier(): Collection
    {
        return $this->panier;
    }

    public function addPanier(Event $event): self
    {
        if (!$this->panier->contains($event)) {
            $this->panier[] = $event;
        }

        return $this;
    }

    public function removePanier(Event $event): self
    {
        $this->panier->removeElement($event);

        return $this;
    }


    public function getTransports(): Collection
    {
        return $this->transports;
    }

    public function addTransport(Transport $transport): self
    {
        if (!$this->transports->contains($transport)) {
            $this->transports->add($transport);
            $transport->addIduser($this);
        }

        return $this;
    }

    public function removeTransport(Transport $transport): self
    {
        if ($this->transports->removeElement($transport)) {
            $transport->removeIduser($this);
        }

        return $this;
    }

    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket$ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setIduser($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            if ($ticket->getIduser() === $this) {
                $ticket->setIduser(null);
            }
        }

        return $this;
    }









}
