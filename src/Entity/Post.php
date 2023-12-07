<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User2;

/**
 * Post
 *
 * @ORM\Table(name="post", indexes={@ORM\Index(name="id_user", columns={"id_user"})})
 * @ORM\Entity
 */
class Post
{   /**
    * @var int
    *
    * @ORM\Column(name="id", type="integer", nullable=false)
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="IDENTITY")
    */
   private $id;

   /**
    * @var int|null
    *
    * @ORM\Column(name="id_post", type="integer", nullable=true, options={"default"="NULL"})
    */
   private $idPost = NULL;

   /**
    * @var string
    *
    * @ORM\Column(name="subject", type="string", length=255, nullable=false)
    */
   private $subject;

   /**
    * @var string
    * @Assert\NotBlank(message="Le titre ne doit pas être vide")
    * @ORM\Column(name="title", type="string", length=255, nullable=false)
    */
   private $title;

   /**
    * @var string
    *
    * @Assert\NotBlank(message="Post ne doit pas être vide")
    * @ORM\Column(name="description_post", type="string", length=800, nullable=false)
    */
   private $descriptionPost;

   /**
     * @var string|null
     *
     * @ORM\Column(name="image_post", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $imagePost = 'NULL';


   /**
    * @var \DateTime
    *
    * @ORM\Column(name="date_creation_post", type="date", nullable=false)
    */
   private $dateCreationPost;

   /**
    * @var int
    *
    * @ORM\Column(name="nbres_comments", type="integer", nullable=false)
    */
   private $nbresComments;

   /**
    * @var \User2
    *
    * @ORM\ManyToOne(targetEntity="User2") 
    * @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="id_user", referencedColumnName="iduser")
    * })
    * 
    
    */
   private $idUser;

   /**
    * @var Collection|Comment[]
    *
    * @ORM\OneToMany(targetEntity="Comment", mappedBy="post", orphanRemoval=true)
    */
   private $comments;

   public function __construct()
   {
       $this->comments = new ArrayCollection();
   }

   // ... (les autres méthodes restent inchangées)

   public function getSubject(): ?string
   {
       return $this->subject;
   }

   public function setSubject(string $subject): static
   {
       $this->subject = $subject;
       return $this;
     
   }
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getIdPost(): ?int
    {
        return $this->idPost;
    }

    public function getIdUser(): ?User2
    {
        return $this->idUser;
    }

    public function setIdUser(User2 $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }


    public function getDescriptionPost(): ?string
    {
        return $this->descriptionPost;
    }

    public function setDescriptionPost(string $descriptionPost): static
    {
        $this->descriptionPost = $descriptionPost;

        return $this;
    }

    public function getImagePost(): ?string
    {
        return $this->imagePost;
    }

    public function setImagePost(?string $ImagePost): static
    {
        $this->imagePost = $ImagePost;

        return $this;
    }

    public function getDateCreationPost(): ?\DateTimeInterface
    {
        return $this->dateCreationPost;
    }

    public function setDateCreationPost(\DateTimeInterface $dateCreationPost): static
    {
        $this->dateCreationPost = $dateCreationPost;

        return $this;
    }

    public function getNbresComments(): ?int
    {
        return $this->nbresComments;
    }

    public function setNbresComments(int $nbresComments): static
    {
        $this->nbresComments = $nbresComments;

        return $this;
    }

    public function prePersist()
    {
        //$this->dateCreationPost = new \DateTime();
        $now = new \DateTime();
        $this->dateCreationPost = \DateTime::createFromFormat('Y-m-d H:i:s', $now->format('Y-m-d H:i:s'));
       
    }


    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setIdPost($this);
            $this->nbresComments++; // Incrémentation du nombre de commentaires
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
            $this->nbresComments--; // Décrémentation du nombre de commentaires
        }

        return $this;
    }
    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function getCommentCount(): int
    {
        return count($this->comments);
    }

    public function getUser(): ?User2
    {
        return $this->idUser;
    }
}
