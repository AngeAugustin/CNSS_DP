<?php

namespace App\Entity;

use App\Repository\NiveauRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NiveauRepository::class)]
class Niveau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $Id_niveau = null;

    #[ORM\Column(length: 200)]
    private ?string $Libelle_niveau = null;

    public function getIdNiveau(): ?int
    {
        return $this->Id_niveau;
    }

    public function getLibelleNiveau(): ?string
    {
        return $this->Libelle_niveau;
    }

    public function setLibelleNiveau(string $Libelle_niveau): static
    {
        $this->Libelle_niveau = $Libelle_niveau;

        return $this;
    }
}
