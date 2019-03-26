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
    private $mapado_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMapadoId(): ?string
    {
        return $this->mapado_id;
    }

    public function setMapadoId(string $mapado_id): self
    {
        $this->mapado_id = $mapado_id;

        return $this;
    }
}
