<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment
 *
 * @ORM\Table(name="comment", indexes={@ORM\Index(name="comment_ibfk_2", columns={"id_user"})})
 * @ORM\Entity
 */
class Comment
{
    /**
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
     * @ORM\Column(name="id_comment", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $idComment = NULL;

    /**
     * @var int
     *
     * @ORM\Column(name="id_post", type="integer", nullable=false)
     */
    private $idPost;

    /**
     * @var string
     * @Assert\NotBlank(message=" Comment ne doit pas étre vide")
     * @ORM\Column(name="description_comment", type="string", length=800, nullable=false)
     */
    private $descriptionComment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation_comment", type="date", nullable=false)
     */
    private $dateCreationComment;

    /**
     * @var int|null
     *
     * @ORM\Column(name="post_id", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $postId = NULL;

    /**
     * @var \User2
     *
     * @ORM\ManyToOne(targetEntity="User2")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="iduser")
     * })
     */
    private $idUser;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdComment(): ?int
    {
        return $this->idComment;
    }

    public function setIdComment(int $idComment): static
    {
        $this->idComment = $idComment;

        return $this;
    }

    public function getIdPost(): ?int
    {
        return $this->idPost;
    }

    public function setIdPost(int $idPost): static
    {
        $this->idPost = $idPost;

        return $this;
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

    public function getDescriptionComment(): ?string
    {
        return $this->descriptionComment;
    }

    public function setDescriptionComment(string $descriptionComment): static
    {
        $this->descriptionComment = $descriptionComment;

        return $this;
    }

    public function getDateCreationComment(): ?\DateTimeInterface
    {
        return $this->dateCreationComment;
    }

    public function setDateCreationComment(\DateTimeInterface $DateCreationComment): static
    {
        $this->dateCreationComment = $DateCreationComment;

        return $this;
    }
    public function prePersist()
    {
        $this->dateCreationComment = new \DateTime();
       
    }

    public function getUser(): ?User2
    {
        return $this->idUser;
    }

}
