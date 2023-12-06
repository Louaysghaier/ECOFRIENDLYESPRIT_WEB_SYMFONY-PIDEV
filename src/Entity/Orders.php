<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table(name="orders", indexes={@ORM\Index(name="fk_idserv", columns={"services"}), @ORM\Index(name="fk_iduser", columns={"userId"})})
 * @ORM\Entity(repositoryClass=OrdersRepository::class)
 */
class Orders
{
    /**
     * @var int
     *
     * @ORM\Column(name="orderId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $orderid;

    /**
     * @var string
     *
     * @ORM\Column(name="num_order", type="string", nullable=false)
     */
    private $numOrder;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="pickupDateTime", type="datetime", nullable=false)
     */
    private $pickupdatetime ;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=false, options={"default"="dispo"})
     */
    private $status = 'dispo';

    /**
     * @var string|null
     *
     * @ORM\Column(name="phonenumber", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $phonenumber = 'NULL';

    /**
     * @var int|null
     *
     * 
     * @ORM\Column(name="priceOrder", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $priceorder = NULL;

    /**
     * @var \User2
     *
     * @ORM\ManyToOne(targetEntity="User2", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userId", referencedColumnName="iduser")
     * })
     */
    private $userid;

    /**
     * @var \Service
     *
     * @ORM\ManyToOne(targetEntity="Service",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="services", referencedColumnName="serviceId")
     * })
     */
    private $services;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Service", inversedBy="orderid")
     * @ORM\JoinTable(name="orderservices",
     *   joinColumns={
     *     @ORM\JoinColumn(name="orderId", referencedColumnName="orderId")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="serviceId", referencedColumnName="serviceId")
     *   }
     * )
     */
    private $serviceid = array();

    /**
     * Constructor
     */

    public function __construct()
    {
        $this->serviceid = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pickupdatetime = new DateTime();

    }

    
    public function getOrderid(): ?int
    {
        return $this->orderid;
    }

    public function getNumOrder(): ?string
    {
        return $this->numOrder;
    }

    public function setNumOrder(string $numOrder): static
    {
        $this->numOrder = $numOrder;

        return $this;
    }
    /**
     * @return DateTime
     */
    public function getPickupdatetime(): DateTime|string
    {
        return $this->pickupdatetime;
    }



    /**
     * @param DateTimeInterface $pickupdatetime
     * @return $this
     */
    public function setPickupdatetime(DateTimeInterface $pickupdatetime): static
    {
        $this->pickupdatetime = $pickupdatetime;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(?string $phonenumber): static
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function getPriceorder(): ?int
    {
        return $this->priceorder;
    }

    public function setPriceorder(?int $priceorder): static
    {
        $this->priceorder = $priceorder;

        return $this;
    }

    public function getUserid(): ?User2
    {
        return $this->userid;
    }

    public function setUserid(?User2 $userid): static
    {
        $this->userid = $userid;

        return $this;
    }

    public function getServices(): ?Service
    {
        return $this->services;
    }

    public function setServices(?Service $services): static
    {
        $this->services = $services;

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServiceid(): Collection
    {
        return $this->serviceid;
    }

    public function addServiceid(Service $serviceid): static
    {
        if (!$this->serviceid->contains($serviceid)) {
            $this->serviceid->add($serviceid);
        }

        return $this;
    }

    public function removeServiceid(Service $serviceid): static
    {
        $this->serviceid->removeElement($serviceid);

        return $this;
    }

    public function __toString(): string
    {
        return $this->numOrder;
    }

}
