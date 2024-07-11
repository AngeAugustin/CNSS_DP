<?php

namespace App\Entity;

use App\Repository\AffectationDossierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AffectationDossierRepository::class)]
class AffectationDossier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $Id_affectation = null;

    #[ORM\Column(length: 200)]
    private ?string $Reference_dossier = null;

    #[ORM\Column]
    private ?int $Id_affecteur = null;

    #[ORM\Column]
    private ?int $Id_affecte = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Date_affectation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Date_traitement = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $Statut_traitement = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $Statut_affectation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Date_retrait = null;

    #[ORM\Column]
    private ?int $Id_niveau = null;

    public function getIdAffectation(): ?int
    {
        return $this->Id_affectation;
    }

    public function getReferenceDossier(): ?string
    {
        return $this->Reference_dossier;
    }

    public function setReferenceDossier(string $Reference_dossier): static
    {
        $this->Reference_dossier = $Reference_dossier;

        return $this;
    }

    public function getIdAffecteur(): ?int
    {
        return $this->Id_affecteur;
    }

    public function setIdAffecteur(int $Id_affecteur): static
    {
        $this->Id_affecteur = $Id_affecteur;

        return $this;
    }

    public function getIdAffecte(): ?int
    {
        return $this->Id_affecte;
    }

    public function setIdAffecte(int $Id_affecte): static
    {
        $this->Id_affecte = $Id_affecte;

        return $this;
    }

    public function getDateAffectation(): ?\DateTimeInterface
    {
        return $this->Date_affectation;
    }

    public function setDateAffectation(?\DateTimeInterface $Date_affectation): static
    {
        $this->Date_affectation = $Date_affectation;

        return $this;
    }

    public function getDateTraitement(): ?\DateTimeInterface
    {
        return $this->Date_traitement;
    }

    public function setDateTraitement(?\DateTimeInterface $Date_traitement): static
    {
        $this->Date_traitement = $Date_traitement;

        return $this;
    }

    public function getStatutTraitement(): ?string
    {
        return $this->Statut_traitement;
    }

    public function setStatutTraitement(?string $Statut_traitement): static
    {
        $this->Statut_traitement = $Statut_traitement;

        return $this;
    }

    public function getStatutAffectation(): ?string
    {
        return $this->Statut_affectation;
    }

    public function setStatutAffectation(?string $Statut_affectation): static
    {
        $this->Statut_affectation = $Statut_affectation;

        return $this;
    }

    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->Date_retrait;
    }

    public function setDateRetrait(?\DateTimeInterface $Date_retrait): static
    {
        $this->Date_retrait = $Date_retrait;

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
}
