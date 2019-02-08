<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PosteRepository")
 * @ApiResource
 */
class Poste
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
    private $Codeposte;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nomposte;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Fonction;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $Etat;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Tension;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=15)
     */
    private $LongitudeposteDD;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=15)
     */
    private $LatitudeposteDD;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeposte(): ?string
    {
        return $this->Codeposte;
    }

    public function setCodeposte(string $Codeposte): self
    {
        $this->Codeposte = $Codeposte;

        return $this;
    }

    public function getNomposte(): ?string
    {
        return $this->Nomposte;
    }

    public function setNomposte(string $Nomposte): self
    {
        $this->Nomposte = $Nomposte;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->Fonction;
    }

    public function setFonction(string $Fonction): self
    {
        $this->Fonction = $Fonction;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->Etat;
    }

    public function setEtat(string $Etat): self
    {
        $this->Etat = $Etat;

        return $this;
    }

    public function getTension(): ?int
    {
        return $this->Tension;
    }

    public function setTension(?int $Tension): self
    {
        $this->Tension = $Tension;

        return $this;
    }

    public function getLongitudeposteDD()
    {
        return $this->LongitudeposteDD;
    }

    public function setLongitudeposteDD($LongitudeposteDD): self
    {
        $this->LongitudeposteDD = $LongitudeposteDD;

        return $this;
    }

    public function getLatitudeposteDD()
    {
        return $this->LatitudeposteDD;
    }

    public function setLatitudeposteDD($LatitudeposteDD): self
    {
        $this->LatitudeposteDD = $LatitudeposteDD;

        return $this;
    }
}
