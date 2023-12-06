<?php

namespace App\Entity;

use App\Repository\CodepromoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Codepromo
 *
 * @ORM\Table(name="codepromo")
 * @ORM\Entity(repositoryClass=CodepromoRepository::class)(repositoryClass=CodepromoRepository::class)
 */
class Codepromo
{
    /**
     * @var int
     *
     * @ORM\Column(name="idcodepromo", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcodepromo;


      /**
    
     
     * @Assert\NotBlank(message=" pourcentage doit etre non vide")
     * 
     *  @ORM\Column(type="float")
     */
    private $pourcentage;

    /**
     * @var int
     *
     * @ORM\Column(name="idevent", type="integer", nullable=true)
     */
    private $idevent;

    /**
     * @var int
     *
     * @ORM\Column(name="idtransport", type="integer", nullable=true)
     */
    private $idtransport;

    /**
     * @var int
     *
     * @ORM\Column(name="idservice", type="integer", nullable=true)
     */
    private $idservice;

    public function getIdcodepromo(): ?int
    {
        return $this->idcodepromo;
    }

    public function getPourcentage(): ?float
    {
        return $this->pourcentage;
    }

    public function setPourcentage(float $pourcentage): static
    {
        $this->pourcentage = $pourcentage;

        return $this;
    }

    public function getIdevent(): ?int
    {
        return $this->idevent;
    }

    public function setIdevent(int $idevent): static
    {
        $this->idevent = $idevent;

        return $this;
    }

    public function getIdtransport(): ?int
    {
        return $this->idtransport;
    }

    public function setIdtransport(int $idtransport): static
    {
        $this->idtransport = $idtransport;

        return $this;
    }

    public function getIdservice(): ?int
    {
        return $this->idservice;
    }

    public function setIdservice(int $idservice): static
    {
        $this->idservice = $idservice;

        return $this;
    }
  


}
