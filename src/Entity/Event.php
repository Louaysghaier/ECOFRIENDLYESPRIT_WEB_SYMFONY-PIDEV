<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;

use Symfony\Component\HttpFoundation\File\File;
use App\Form\TimeType;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\DateTime;




/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */


class Event
{
    /**
     * @var int
     *
     * @ORM\Column(name="idEvent", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idevent;

    /**
 * @var string
 * 
 * @ORM\Column(name="LieuEvent", type="string", length=100, nullable=false)
 * @Assert\NotBlank(message="Le lieu de l'événement ne peut pas être vide.")
 * @Assert\Length(
 *     min=5,
 *     minMessage="Le lieu de l'événement doit avoir au moins {{ limit }} caractères."
 * )
 */
private $lieuevent;


/**
 * @var \DateTime
 *
 * @ORM\Column(name="datedebutevent", type="datetime", nullable=false)
 * @Assert\NotBlank(message="La date de début de l'événement ne peut pas être vide.")
 */
private $datedebutevent;



    /**
     * @var string
     *
     * @ORM\Column(name="Duree", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="La durée de l'événement ne peut pas être vide.")

     */
    private $duree;

    /**
     * @var string
     *
     * @ORM\Column(name="nbmaxParticipant", type="string", length=100, nullable=false)
     * @Assert\NotBlank(message="Le nombre maximum de participants ne peut pas être vide.")
     */
    private $nbmaxparticipant;

    /**
     * @var string
     *
     * @ORM\Column(name="PrixTicket", type="string", length=100, nullable=false)
     * @Assert\NotBlank(message="Le prix du ticket ne peut pas être vide.")
     */
    private $prixticket;

  

     

    /**
     * @var string
     *
     * @ORM\Column(name="nomEvent", type="string", length=100, nullable=false)
     
     
     * @Assert\NotBlank(message="Le nom de l'événement ne peut pas être vide.")
     * @Assert\Length(
     *     min=5,
     *     minMessage="Le nom de l'événement doit avoir au moins {{ limit }} caractères."
     * )
     */
    private $nomevent;

    /**
 * @var string
 *
 * @ORM\Column(name="typeEvent", type="string", length=100, nullable=false)
 * @Assert\NotBlank(message="Le type de l'événement ne peut pas être vide.")
 * @Assert\Length(
 *     min=5,
 *     minMessage="Le type de l'événement doit avoir au moins {{ limit }} caractères."
 * )
 */
private $typeevent;
    
/**
 * @var string
 *
 * @ORM\Column(name="descriptionEvent", type="string", length=255, nullable=false)
 * @Assert\NotBlank(message="La description de l'événement ne peut pas être vide.")
 * @Assert\Length(
 *     min=5,
 *     minMessage="La description de l'événement doit avoir au moins {{ limit }} caractères."
 * )
 */
private $descriptionevent;
    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=100, nullable=true)
     * @Assert\File(
     *    
     *    
     *     
     *     
     *     )
     */
    private $image;



    

/**
 * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
 * @Gedmo\Timestampable(on="update")
 */
private $datecreation;



 
    /**
     * @var int|null
     *
     * @ORM\Column(name="iduser", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $iduser = NULL;

 
    /**
    * @ORM\Column(name="valid", type="boolean", nullable=true, options={"default"=true})
    */
      private $valid = true;



/**
 * @var int
 *
 * @ORM\Column(name="current_nb_participants", type="integer", nullable=true, options={"default"=0})
 */
private $NbParticipants = 0;




    public function getIdevent(): ?int
    {
        return $this->idevent;
    }

    public function getLieuevent(): ?string
    {
        return $this->lieuevent;
    }

    public function setLieuevent(string $lieuevent): static
    {
        $this->lieuevent = $lieuevent;

        return $this;
    }

   /**
 * @return \DateTime
 */
public function getDatedebutevent(): ?\DateTime
{
    return $this->datedebutevent;
}

/**
 * @param \DateTime $datedebutevent
 */
public function setDatedebutevent(\DateTime $datedebutevent): void
{
    $this->datedebutevent = $datedebutevent;
}


    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getNbmaxparticipant(): ?string
    {
        return $this->nbmaxparticipant;
    }

    public function setNbmaxparticipant(string $nbmaxparticipant): static
    {
        $this->nbmaxparticipant = $nbmaxparticipant;

        return $this;
    }

    public function getPrixticket(): ?string
    {
        return $this->prixticket;
    }

    public function setPrixticket(string $prixticket): static
    {
        $this->prixticket = $prixticket;

        return $this;
    }

    public function getNomevent(): ?string
    {
        return $this->nomevent;
    }

    public function setNomevent(string $nomevent): static
    {
        $this->nomevent = $nomevent;

        return $this;
    }

    public function getTypeevent(): ?string
    {
        return $this->typeevent;
    }

    public function setTypeevent(string $typeevent): static
    {
        $this->typeevent = $typeevent;

        return $this;
    }

    public function getDescriptionevent(): ?string
    {
        return $this->descriptionevent;
    }

    public function setDescriptionevent(string $descriptionevent): static
    {
        $this->descriptionevent = $descriptionevent;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(): void
    {
        $this->datecreation = new \DateTime();
    }


      /**
     * @return bool|null
     */
    public function getValid(): ?bool
    {
        return $this->valid;
    }

    /**
     * @param bool|null $valid
     */
    public function setValid(?bool $valid): void
    {
        $this->valid = $valid;
    }


/**
 * @return int|null
 */
public function getNbParticipants(): ?int
{
    return $this->NbParticipants;
}

/**
 * @param int|null $NbParticipants
 */
public function setNbParticipants(?int $NbParticipants): void
{
    $this->NbParticipants = $NbParticipants;
}

    
    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(?int $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }





 
   

    /**
     * This property will hold the uploaded file during the form submission.
     */
    private $imageFile;

    // Existing getters and setters...

   

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile($imageFile): void
    {
        $this->imageFile = $imageFile;
    }
   
    
    }

    

    


