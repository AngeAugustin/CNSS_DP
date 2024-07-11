<?php

namespace App\Entity;

use App\Repository\ActeurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActeurRepository::class)]
class Acteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $Id_acteur = null;

    #[ORM\Column]
    private ?int $Id_agence = null;

    #[ORM\Column]
    private ?int $Id_niveau = null;

    #[ORM\Column(length: 200)]
    private ?string $Username = null;

    #[ORM\Column(length: 200)]
    private ?string $Password = null;

    public function getIdActeur(): ?int
    {
        return $this->Id_acteur;
    }

    public function getIdAgence(): ?int
    {
        return $this->Id_agence;
    }

    public function setIdAgence(int $Id_agence): static
    {
        $this->Id_agence = $Id_agence;

        return $this;
    }

    public function getIdNiveau(): ?int
    {
        return $this->Id_niveau;
    }

    public function setIdNiveau(int $Id_niveau): static
    {
        $this->Id_niveau = $Id_niveau;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    public function setUsername(string $Username): static
    {
        $this->Username = $Username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): static
    {
        $this->Password = $Password;

        return $this;
    }

    //Validité du password ?
    public function isValidPassword(string $Password): bool
    {
        return $this->Password === $Password;
    }
}
