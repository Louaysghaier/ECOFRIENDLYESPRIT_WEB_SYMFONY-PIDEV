<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Service
 * @ORM\Table(name="service")
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 */
class Service
{
    /**
     * @var int
     *
     * @ORM\Column(name="serviceId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $serviceid;

    /**
     * @var string
     * @Assert\NotBlank(message="Service name cannot be blank.")
     *   @ORM\Column(name="serviceName", type="string", length=100, nullable=false)
     */
    private $servicename;

    /**
     * @var float
     * @Assert\NotBlank(message="Price cannot be blank.")
     * @Assert\Type(type="numeric", message="Price must be a valid number.")
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=200, nullable=false)
     * @Assert\File(
     * maxSize="10M",
     * maxSizeMessage="The file is too large. Maximum allowed size is 5MB.",
     * mimeTypes={"image/*"},
     * mimeTypesMessage="Please upload a valid image file."
     * )
     */

    private $img;
    /**
     * @var string
     * @Assert\NotBlank(message=" cannot be blank.")
     * @ORM\Column(name="description", type="string", length=30, nullable=true)
     * */

    private $description;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Orders", mappedBy="serviceid", cascade={"persist", "remove"})
     */
    private $orderid = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orderid = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getServiceid(): ?int
    {
        return $this->serviceid;
    }

    public function getServicename(): ?string
    {
        return $this->servicename;
    }

    public function setServicename(string $servicename): static
    {
        $this->servicename = $servicename;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): static
    {
        $this->img = $img;

        return $this;
    }

    /**
     * @return Collection<int, Orders>
     */
    public function getOrderid(): Collection
    {
        return $this->orderid;
    }

    public function addOrderid(Orders $orderid): static
    {
        if (!$this->orderid->contains($orderid)) {
            $this->orderid->add($orderid);
            $orderid->addServiceid($this);
        }

        return $this;
    }

    public function removeOrderid(Orders $orderid): static
    {
        if ($this->orderid->removeElement($orderid)) {
            $orderid->removeServiceid($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->servicename;
    }
}
