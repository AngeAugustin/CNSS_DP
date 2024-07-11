<?php

namespace App\Entity;

use App\Repository\AgenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgenceRepository::class)]
class Agence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $Id_agence = null;

    #[ORM\Column(length: 200)]
    private ?string $Libelle_agence = null;

    public function getIdAgence(): ?int
    {
        return $this->Id_agence;
    }

    public function getLibelleAgence(): ?string
    {
        return $this->Libelle_agence;
    }

    public function setLibelleAgence(string $Libelle_agence): static
    {
        $this->Libelle_agence = $Libelle_agence;

        return $this;
    }
}
