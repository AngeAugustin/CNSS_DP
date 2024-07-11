<?php

namespace App\Entity;

use App\Repository\DossierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DossierRepository::class)]
class Dossier
{
    #[ORM\Id]
    #[ORM\Column(length: 20)]
    private ?string $Reference_dossier = null;

    #[ORM\Column(length: 200)]
    private ?string $Numero_pensionne = null;

    #[ORM\Column(length: 200)]
    private ?string $Numero_assure = null;

    #[ORM\Column(length: 20)]
    private ?string $NPI = null;

    #[ORM\Column]
    private ?int $Id_agence = null;

    #[ORM\Column(length: 200)]
    private ?string $Nom_pensionne = null;

    #[ORM\Column(length: 200)]
    private ?string $Prenom_pensionne = null;

    #[ORM\Column(length: 200)]
    private ?string $Statut_global_dossier = null;

    #[ORM\Column(length: 200)]
    private ?string $Adresse_pensionne = null;

    #[ORM\Column(length: 20)]
    private ?string $Telephone_pensionne = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $Acte_deces = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $Acte_mariage = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $Acte_naissance_survivant1 = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $Acte_naissance_survivant2 = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $Acte_naissance_survivant3 = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $Acte_naissance_survivant4 = null;

    #[ORM\Column(length: 200)]
    private ?string $Type_dossier = null;

    public function getReferenceDossier(): ?string
    {
        return $this->Reference_dossier;
    }

    public function setReferenceDossier(string $Reference_dossier): static
    {
        $this->Reference_dossier = $Reference_dossier;

        return $this;
    }

    public function getNumeroPensionne(): ?string
    {
        return $this->Numero_pensionne;
    }

    public function setNumeroPensionne(string $Numero_pensionne): static
    {
        $this->Numero_pensionne = $Numero_pensionne;

        return $this;
    }

    public function getNumeroAssure(): ?string
    {
        return $this->Numero_assure;
    }

    public function setNumeroAssure(string $Numero_assure): static
    {
        $this->Numero_assure = $Numero_assure;

        return $this;
    }

    public function getNPI(): ?string
    {
        return $this->NPI;
    }

    public function setNPI(string $NPI): static
    {
        $this->NPI = $NPI;

        return $this;
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

    public function getNomPensionne(): ?string
    {
        return $this->Nom_pensionne;
    }

    public function setNomPensionne(string $Nom_pensionne): static
    {
        $this->Nom_pensionne = $Nom_pensionne;

        return $this;
    }

    public function getPrenomPensionne(): ?string
    {
        return $this->Prenom_pensionne;
    }

    public function setPrenomPensionne(string $Prenom_pensionne): static
    {
        $this->Prenom_pensionne = $Prenom_pensionne;

        return $this;
    }

    public function getStatutGlobalDossier(): ?string
    {
        return $this->Statut_global_dossier;
    }

    public function setStatutGlobalDossier(string $Statut_global_dossier): static
    {
        $this->Statut_global_dossier = $Statut_global_dossier;

        return $this;
    }

    public function getAdressePensionne(): ?string
    {
        return $this->Adresse_pensionne;
    }

    public function setAdressePensionne(string $Adresse_pensionne): static
    {
        $this->Adresse_pensionne = $Adresse_pensionne;

        return $this;
    }

    public function getTelephonePensionne(): ?string
    {
        return $this->Telephone_pensionne;
    }

    public function setTelephonePensionne(string $Telephone_pensionne): static
    {
        $this->Telephone_pensionne = $Telephone_pensionne;

        return $this;
    }

    public function getActeDeces()
    {
        return $this->Acte_deces;
    }

    public function setActeDeces($Acte_deces): static
    {
        $this->Acte_deces = $Acte_deces;

        return $this;
    }

    public function getActeMariage()
    {
        return $this->Acte_mariage;
    }

    public function setActeMariage($Acte_mariage): static
    {
        $this->Acte_mariage = $Acte_mariage;

        return $this;
    }

    public function getActeNaissanceSurvivant1()
    {
        return $this->Acte_naissance_survivant1;
    }

    public function setActeNaissanceSurvivant1($Acte_naissance_survivant1): static
    {
        $this->Acte_naissance_survivant1 = $Acte_naissance_survivant1;

        return $this;
    }

    public function getActeNaissanceSurvivant2()
    {
        return $this->Acte_naissance_survivant2;
    }

    public function setActeNaissanceSurvivant2($Acte_naissance_survivant2): static
    {
        $this->Acte_naissance_survivant2 = $Acte_naissance_survivant2;

        return $this;
    }

    public function getActeNaissanceSurvivant3()
    {
        return $this->Acte_naissance_survivant3;
    }

    public function setActeNaissanceSurvivant3($Acte_naissance_survivant3): static
    {
        $this->Acte_naissance_survivant3 = $Acte_naissance_survivant3;

        return $this;
    }

    public function getActeNaissanceSurvivant4()
    {
        return $this->Acte_naissance_survivant4;
    }

    public function setActeNaissanceSurvivant4($Acte_naissance_survivant4): static
    {
        $this->Acte_naissance_survivant4 = $Acte_naissance_survivant4;

        return $this;
    }

    public function getTypeDossier(): ?string
    {
        return $this->Type_dossier;
    }

    public function setTypeDossier(string $Type_dossier): static
    {
        $this->Type_dossier = $Type_dossier;

        return $this;
    }
}
