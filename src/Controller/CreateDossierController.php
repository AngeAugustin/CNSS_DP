<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Entity\VieDossier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use DateTime;

class CreateDossierController extends AbstractController
{
    #[Route('/createDossier/{Id_acteur}/{Id_agence}', name: 'create_dossier', methods: ['POST'])]
    public function createDossier(Request $request, EntityManagerInterface $entityManager, $Id_acteur, $Id_agence): JsonResponse
    { 
        $Id_acteur = (int)$Id_acteur;
        $Id_agence = (int)$Id_agence;

        // Vérifier si l'Id_acteur est disponible 
        if ($Id_acteur === null) {
            return new JsonResponse(['error' => 'Id_acteur non défini'], Response::HTTP_NOT_FOUND);
        }

        if ($Id_agence === null) {
            return new JsonResponse(['error' => 'Id_agence non défini'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
    
        if ($data === null) {
            return new JsonResponse(['error' => 'Invalid JSON format'], Response::HTTP_JSON_INVALID);
        }
    
        // Existence d'un dossier avec le meme Reference
        $existingReference = $entityManager->getRepository(Dossier::class)->findOneBy(['Reference_dossier' => $data['Reference_dossier']]);
        if ($existingReference) {
            return new JsonResponse(['error' => 'Dossier avec les mêmes informations existe déjà'], Response::HTTP_BAD_REQUEST);
        }

        // Existence d'un dossier avec le meme numero d'assure
        $existingNumAssure = $entityManager->getRepository(Dossier::class)->findOneBy(['Numero_assure' => $data['Numero_assure']]);
        if ($existingNumAssure) {
            return new JsonResponse(['error' => 'Dossier avec les mêmes informations existe déjà'], Response::HTTP_BAD_REQUEST);
        }

         // Existence d'un dossier avec le meme numero de pensionne
         $existingNumPensionne = $entityManager->getRepository(Dossier::class)->findOneBy(['Numero_pensionne' => $data['Numero_pensionne']]);
         if ($existingNumPensionne) {
             return new JsonResponse(['error' => 'Dossier avec les mêmes informations existe déjà'], Response::HTTP_BAD_REQUEST);
         }

        // Existence d'un dossier avec le meme numero de pensionn
        $existingNPI = $entityManager->getRepository(Dossier::class)->findOneBy(['NPI' => $data['NPI']]);
        if ($existingNPI) {
            return new JsonResponse(['error' => 'Dossier avec le même NPI existe déjà'], Response::HTTP_BAD_REQUEST);
        }
    
        // Enregistrement dans Dossier
        $dossier = new Dossier();
        $dossier->setReferenceDossier($data['Reference_dossier']);
        $dossier->setTypeDossier($data['Type_dossier']);
        $dossier->setNumeroPensionne($data['Numero_pensionne']);
        $dossier->setNumeroAssure($data['Numero_assure']);
        $dossier->setNomPensionne($data['Nom_pensionne']);
        $dossier->setPrenomPensionne($data['Prenom_pensionne']);
        $dossier->setTelephonePensionne($data['Telephone_pensionne']);
        $dossier->setAdressePensionne($data['Adresse_pensionne']);
        $dossier->setStatutGlobalDossier('Nouveau');
        $dossier->setIdAgence($Id_agence);
        $dossier->setNPI($data['NPI']);

         // Optionnel : ajout des actes si fournis
         if (isset($data['Acte_deces'])) {
            $dossier->setActeDeces($data['Acte_deces']);
        }

        if (isset($data['Acte_mariage'])) {
            $dossier->setActeMariage($data['Acte_mariage']);
        }

        if (isset($data['Acte_naissance_survivant1'])) {
            $dossier->setActeNaissanceSurvivant1($data['Acte_naissance_survivant1']);
        }
        
        if (isset($data['Acte_naissance_survivant2'])) {
            $dossier->setActeNaissanceSurvivant2($data['Acte_naissance_survivant2']);
        }

        if (isset($data['Acte_naissance_survivant3'])) {
            $dossier->setActeNaissanceSurvivant3($data['Acte_naissance_survivant3']);
        }
        
        if (isset($data['Acte_naissance_survivant4'])) {
            $dossier->setActeNaissanceSurvivant4($data['Acte_naissance_survivant4']);
        }

        $entityManager->persist($dossier);
        $entityManager->flush();
        
    
        // Conversion des chaînes de dates en objets DateTime
        if (isset($data['Date_enregistrement'])) {
            try {
                $data['Date_enregistrement'] = new DateTime($data['Date_enregistrement']);
            } catch (\Exception $e) {
                return new JsonResponse(['error' => 'Date_enregistrement invalide'], Response::HTTP_INVALIDE_DATE);
            }
        }
    
        if (isset($data['Date_traitement'])) {
            try {
                $data['Date_traitement'] = new DateTime($data['Date_traitement']);
            } catch (\Exception $e) {
                return new JsonResponse(['error' => 'Date_traitement invalide'], Response::HTTP_INVALIDE_DATE);
            }
        }

        if (isset($data['Date_entree'])) {
            try {
                $data['Date_entree'] = new DateTime($data['Date_entree']);
            } catch (\Exception $e) {
                return new JsonResponse(['error' => 'Date_entree invalide'], Response::HTTP_INVALIDE_DATE);
            }
        }

        // Premier Enregistrement dans la table VieDossier
        $vieDossier = new VieDossier();
        $vieDossier->setReferenceDossier($data['Reference_dossier']);
        $vieDossier->setIdActeur($Id_acteur);
        $vieDossier->setIdNiveau($data['Id_niveau']); 
        $vieDossier->setDateEnregistrement($data['Date_enregistrement']);
        $vieDossier->setDateTraitement($data['Date_traitement']);
        $vieDossier->setStatutNiveauDossier('Nouveau');
        $vieDossier->setDateEntree($data['Date_entree']);
        $entityManager->persist($vieDossier);
        $entityManager->flush();
    
        return new JsonResponse(['Message' => 'Enregistrement reussi', 'Reference_dossier' => $dossier->getReferenceDossier()]);
    }
    
}
