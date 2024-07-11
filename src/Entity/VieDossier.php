<?php

namespace App\Entity;

use App\Repository\VieDossierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VieDossierRepository::class)]
class VieDossier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $Id_vie_dossier = null;

    #[ORM\Column(length: 20)]
    private ?string $Reference_dossier = null;

    #[ORM\Column]
    private ?int $Id_niveau = null;

    #[ORM\Column]
    private ?int $Id_acteur = null;

    #[ORM\Column(length: 200)]
    private ?string $Statut_niveau_dossier = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Date_enregistrement = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Date_traitement = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Date_entree = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $Motif_rejet = null;

    public function getIdVieDossier(): ?int
    {
        return $this->Id_vie_dossier;
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

    public function getIdNiveau(): ?int
    {
        return $this->Id_niveau;
    }

    public function setIdNiveau(int $Id_niveau): static
    {
        $this->Id_niveau = $Id_niveau;

        return $this;
    }

    public function getIdActeur(): ?int
    {
        return $this->Id_acteur;
    }

    public function setIdActeur(int $Id_acteur): static
    {
        $this->Id_acteur = $Id_acteur;

        return $this;
    }

    public function getStatutNiveauDossier(): ?string
    {
        return $this->Statut_niveau_dossier;
    }

    public function setStatutNiveauDossier(string $Statut_niveau_dossier): static
    {
        $this->Statut_niveau_dossier = $Statut_niveau_dossier;

        return $this;
    }

    public function getDateEnregistrement(): ?\DateTimeInterface
    {
        return $this->Date_enregistrement;
    }

    public function setDateEnregistrement(\DateTimeInterface $Date_enregistrement): static
    {
        $this->Date_enregistrement = $Date_enregistrement;

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

    public function getDateEntree(): ?\DateTimeInterface
    {
        return $this->Date_entree;
    }

    public function setDateEntree(?\DateTimeInterface $Date_entree): static
    {
        $this->Date_entree = $Date_entree;

        return $this;
    }

    public function getMotifRejet(): ?string
    {
        return $this->Motif_rejet;
    }

    public function setMotifRejet(?string $Motif_rejet): static
    {
        $this->Motif_rejet = $Motif_rejet;

        return $this;
    }
}
