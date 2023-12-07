<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Raiting
 *
 * @ORM\Table(name="raiting", indexes={@ORM\Index(name="iduser", columns={"iduser"}), @ORM\Index(name="id", columns={"id"})})
 * @ORM\Entity
 */
class Raiting
{
    /**
     * @var int
     *
     * @ORM\Column(name="idraiting", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idraiting;

    /**
     * @var int
     *
     * @ORM\Column(name="value", type="integer", nullable=false)
     */
    private $value;

    /**
     * @var \Documents
     *
     * @ORM\ManyToOne(targetEntity="Documents",cascade={"remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $id;

    /**
     * @var \User2
     *
     * @ORM\ManyToOne(targetEntity="User2",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iduser", referencedColumnName="iduser")
     * })
     */
    private $iduser;

    public function getIdraiting(): ?int
    {
        return $this->idraiting;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getId(): ?Documents
    {
        return $this->id;
    }

    public function setId(?Documents $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getIduser(): ?User2
    {
        return $this->iduser;
    }

    public function setIduser(?User2 $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }


}
