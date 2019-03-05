<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventbriteIDsRepository")
 */
class EventbriteIDs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $venue_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVenueId(): ?int
    {
        return $this->venue_id;
    }

    public function setVenueId(int $venue_id): self
    {
        $this->venue_id = $venue_id;

        return $this;
    }
}
