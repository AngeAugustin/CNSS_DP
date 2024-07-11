<?php

namespace App\Entity;

use App\Repository\BordereauRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BordereauRepository::class)]
class Bordereau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $Id_bordereau = null;

    #[ORM\Column]
    private ?int $Id_expediteur = null;

    #[ORM\Column]
    private ?int $Id_destinataire = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Date_transmission = null;

    #[ORM\Column(length: 200)]
    private ?string $Reference_dossier = null;

    #[ORM\Column]
    private ?int $Id_agence_expedition = null;

    #[ORM\Column]
    private ?int $Id_agence_destination = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $Statut_transmission = null;

    public function getIdBordereau(): ?int
    {
        return $this->Id_bordereau;
    }

    public function getIdExpediteur(): ?int
    {
        return $this->Id_expediteur;
    }

    public function setIdExpediteur(int $Id_expediteur): static
    {
        $this->Id_expediteur = $Id_expediteur;

        return $this;
    }

    public function getIdDestinataire(): ?int
    {
        return $this->Id_destinataire;
    }

    public function setIdDestinataire(int $Id_destinataire): static
    {
        $this->Id_destinataire = $Id_destinataire;

        return $this;
    }

    public function getDateTransmission(): ?\DateTimeInterface
    {
        return $this->Date_transmission;
    }

    public function setDateTransmission(\DateTimeInterface $Date_transmission): static
    {
        $this->Date_transmission = $Date_transmission;

        return $this;
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

    public function getIdAgenceExpedition(): ?int
    {
        return $this->Id_agence_expedition;
    }

    public function setIdAgenceExpedition(int $Id_agence_expedition): static
    {
        $this->Id_agence_expedition = $Id_agence_expedition;

        return $this;
    }

    public function getIdAgenceDestination(): ?int
    {
        return $this->Id_agence_destination;
    }

    public function setIdAgenceDestination(int $Id_agence_destination): static
    {
        $this->Id_agence_destination = $Id_agence_destination;

        return $this;
    }

    public function getStatutTransmission(): ?string
    {
        return $this->Statut_transmission;
    }

    public function setStatutTransmission(?string $Statut_transmission): static
    {
        $this->Statut_transmission = $Statut_transmission;

        return $this;
    }
}
