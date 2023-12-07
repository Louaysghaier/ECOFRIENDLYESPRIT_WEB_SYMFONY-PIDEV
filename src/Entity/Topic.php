<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Topic
 *
 * @ORM\Table(name="topic")
 * @ORM\Entity
 */
class Topic
{
    /**
     * @var int
     *
     * @ORM\Column(name="idtopic", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtopic;

    /**
     * @var string
     *
     * @ORM\Column(name="topic_name", type="string", length=255, nullable=false)
     */
    private $topicName;

    public function getIdtopic(): ?int
    {
        return $this->idtopic;
    }

    public function getTopicName(): ?string
    {
        return $this->topicName;
    }

    public function setTopicName(string $topicName): static
    {
        $this->topicName = $topicName;

        return $this;
    }


}
