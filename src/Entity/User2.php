<?php

namespace App\Entity;

use App\Repository\User2Repository;
use Doctrine\ORM\Mapping as ORM;

/**
 * User2
 *
 * @ORM\Table(name="user2")
 * @ORM\Entity(repositoryClass=User2Repository::class)
 */
class User2
{
    /**
     * @var int
     *
     * @ORM\Column(name="iduser", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iduser;

    /**
     * @var string
     *
     * @ORM\Column(name="nomuser", type="string", length=100, nullable=false)
     */
    private $nomuser;

    /**
     * @var string
     *
     * @ORM\Column(name="prenomuser", type="string", length=100, nullable=false)
     */
    private $prenomuser;

    /**
     * @var string
     *
     * @ORM\Column(name="mailuser", type="string", length=200, nullable=false)
     */
    private $mailuser;

    /**
     * @var string
     *
     * @ORM\Column(name="mdpuser", type="string", length=300, nullable=false)
     */
    private $mdpuser;

    /**
     * @var string
     *
     * @ORM\Column(name="adressuser", type="string", length=200, nullable=false)
     */
    private $adressuser;

    /**
     * @var float
     *
     * @ORM\Column(name="walletuser", type="float", precision=10, scale=0, nullable=false, options={"default"="250"})
     */
    private $walletuser = 250;

    /**
     * @var string
     *
     * @ORM\Column(name="classeuser", type="string", length=200, nullable=false)
     */
    private $classeuser;

    /**
     * @var string|null
     *
     * @ORM\Column(name="roleuser", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $roleuser = 'NULL';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="isBlocked", type="boolean", nullable=true)
     */
    private $isblocked = '0';

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

    public function isIsblocked(): ?bool
    {
        return $this->isblocked;
    }

    public function setIsblocked(?bool $isblocked): static
    {
        $this->isblocked = $isblocked;

        return $this;
    }

    public function __toString(): string
    {
        return $this->nomuser . ' ' . $this->prenomuser;
    }


}
