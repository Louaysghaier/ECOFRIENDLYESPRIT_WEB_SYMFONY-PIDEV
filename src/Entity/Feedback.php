<?php

namespace App\Entity;

use App\Repository\FeedbackRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Feedback
 *
 * @ORM\Table(name="feedback")
 * @ORM\Entity(repositoryClass=FeedbackRepository::class)
 */
class Feedback
{
    /**
     * @var int
     *
     * @ORM\Column(name="feeds", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $feeds;

    /**
     * @var string
     *
     * @ORM\Column(name="oreder_id", type="string", length=255, nullable=false)
     */
    private $orederId;

    /**
     * @var string
     *
     * @ORM\Column(name="service_id", type="string", length=255, nullable=false)
     */
    private $serviceId;

    /**
     * @var string
     *
     * @ORM\Column(name="comments", type="string", length=255, nullable=false)
     */
    private $comments;

    public function getFeeds(): ?int
    {
        return $this->feeds;
    }

    public function getOrederId(): ?string
    {
        return $this->orederId;
    }

    public function setOrederId(string $orederId): static
    {
        $this->orederId = $orederId;

        return $this;
    }

    public function getServiceId(): ?string
    {
        return $this->serviceId;
    }

    public function setServiceId(string $serviceId): static
    {
        $this->serviceId = $serviceId;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(string $comments): static
    {
        $this->comments = $comments;

        return $this;
    }


}
