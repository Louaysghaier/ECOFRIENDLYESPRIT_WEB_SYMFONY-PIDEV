<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Documents
 *
 * @ORM\Table(name="documents", indexes={@ORM\Index(name="idtopic", columns={"idtopic"}), @ORM\Index(name="iduser", columns={"iduser"})})
 * @ORM\Entity
 */
class Documents
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
     * @var string
     *
     * @ORM\Column(name="document_name", type="string", length=255, nullable=false)
     */
    private $documentName;

    /**
     * @var string
     *
     * @ORM\Column(name="document_type", type="string", length=255, nullable=false)
     */
    private $documentType;

    /**
     * @var string
     *
     * @ORM\Column(name="document", type="string", length=255, nullable=false)
     */
    private $document;

    /**
     * @var string
     *
     * @ORM\Column(name="niveau", type="string", length=255, nullable=false)
     */
    private $niveau;

    /**
     * @var string
     *
     * @ORM\Column(name="semestre", type="string", length=255, nullable=false)
     */
    private $semestre;

    /**
     * @var string
     *
     * @ORM\Column(name="brochureFilename", type="string", length=255, nullable=false)
     */
    private $brochurefilename;

    /**
     * @var \User2
     *
     * @ORM\ManyToOne(targetEntity="User2",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iduser", referencedColumnName="iduser")
     * })
     */
    private $iduser;

    /**
     * @var \Topic
     *
     * @ORM\ManyToOne(targetEntity="Topic",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idtopic", referencedColumnName="idtopic")
     * })
     */
    private $idtopic;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDocumentName(): ?string
    {
        return $this->documentName;
    }

    public function setDocumentName(string $documentName): static
    {
        $this->documentName = $documentName;

        return $this;
    }

    public function getDocumentType(): ?string
    {
        return $this->documentType;
    }

    public function setDocumentType(string $documentType): static
    {
        $this->documentType = $documentType;

        return $this;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(string $document): static
    {
        $this->document = $document;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getSemestre(): ?string
    {
        return $this->semestre;
    }

    public function setSemestre(string $semestre): static
    {
        $this->semestre = $semestre;

        return $this;
    }

    public function getBrochurefilename(): ?string
    {
        return $this->brochurefilename;
    }

    public function setBrochurefilename(string $brochurefilename): static
    {
        $this->brochurefilename = $brochurefilename;

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

    public function getIdtopic(): ?Topic
    {
        return $this->idtopic;
    }

    public function setIdtopic(?Topic $idtopic): static
    {
        $this->idtopic = $idtopic;

        return $this;
    }

    public function __toString()
    {
        // Customize this method to return a meaningful string representation of the entity
        return sprintf(
            'Document Name: %s, Document Type: %s, Niveau: %s, Semestre: %s, Topic: %s',
            $this->getDocumentName(),
            $this->getDocumentType(),
            $this->getNiveau(),
            $this->getSemestre(),
            $this->getIdtopic()->getTopicName()
        );
    }
    
}