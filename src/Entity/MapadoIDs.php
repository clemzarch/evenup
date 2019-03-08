<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MapadoIDsRepository")
 */
class MapadoIDs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $venue_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVenueId(): ?string
    {
        return $this->venue_id;
    }

    public function setVenueId(string $venue_id): self
    {
        $this->venue_id = $venue_id;

        return $this;
    }
}
