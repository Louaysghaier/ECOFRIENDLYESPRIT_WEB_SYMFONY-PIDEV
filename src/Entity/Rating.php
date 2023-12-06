<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Rating
 *
 * @ORM\Table(name="rating")
 * @ORM\Entity(repositoryClass=RatingRepository::class)
 */
class Rating
{
    /**
     * @var int
     *
     * @ORM\Column(name="rate_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $rateId;



    /**
     * @var string
     *
     * @ORM\Column(name="service", type="string", length=255, nullable=false)
     */
    private $service;

    /**
     * @var int
     *
     * @ORM\Column(name="stars", type="integer", nullable=false)
     */
    private $stars;

    public function getRateId(): ?int
    {
        return $this->rateId;
    }

    public function getOrders(): ?string
    {
        return $this->orders;
    }

    public function setOrders(string $orders): static
    {
        $this->orders = $orders;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(string $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getStars(): ?int
    {
        return $this->stars;
    }

    public function setStars(int $stars): static
    {
        $this->stars = $stars;

        return $this;
    }


}
