<?php

namespace App\Controller;

use App\Entity\VieDossier;
use App\Entity\AffectationDossier;
use App\Entity\Bordereau;
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

class RejectController extends AbstractController
{
    #[Route('/rejeter/{Reference_dossier}/{Id_niveau}/{Id_acteur}/{Motif_rejet}', name: 'reject', methods: ['POST'])]
    public function reject(Request $request, $Reference_dossier, $Id_niveau, $Id_acteur, $Motif_rejet, EntityManagerInterface $entityManager): JsonResponse
    {
        $Reference_dossier = (string)$Reference_dossier;
        $Id_niveau = (int)$Id_niveau;
        $Id_acteur = (int)$Id_acteur;

        // Num_assure 
        if (!$Reference_dossier) {
            return new JsonResponse(['error' => 'Reference_dossier non trouvé'], Response::HTTP_NOT_FOUND);
        }
        // Id_niveau 
        if (!$Id_niveau) {
            return new JsonResponse(['error' => 'Id_niveau non trouvé'], Response::HTTP_NOT_FOUND);
        }
         // Id_acteur
         if (!$Id_acteur) {
            return new JsonResponse(['error' => 'Id_acteur non trouvé'], Response::HTTP_NOT_FOUND);
        }
        // Motif_rejet 
        if (!$Motif_rejet) {
            return new JsonResponse(['error' => 'Motif_rejet non trouvé'], Response::HTTP_NOT_FOUND);
        }

        // Recherche de la date du premier enregistrement pour ce Num_assure
        $premierEnregistrement = $entityManager->getRepository(VieDossier::class)
        ->createQueryBuilder('vd')
        ->select('vd.Date_enregistrement')
        ->where('vd.Reference_dossier = :referenceDossier')
        ->setParameter('referenceDossier', $Reference_dossier)
        ->orderBy('vd.Date_enregistrement', 'ASC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
        // Si un enregistrement existe, utiliser sa date, sinon utiliser la date actuelle
        $dateEnregistrement = $premierEnregistrement ? $premierEnregistrement['Date_enregistrement'] : new \DateTime();

        // Recherche de la dernière date de traitement pour ce Num_assure
        $dernierTraitement = $entityManager->getRepository(VieDossier::class)
        ->createQueryBuilder('vd')
        ->select('vd.Date_traitement')
        ->where('vd.Reference_dossier = :referenceDossier')
        ->setParameter('referenceDossier', $Reference_dossier)
        ->orderBy('vd.Date_traitement', 'DESC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
        $dateDernierTraitement = $dernierTraitement ? $dernierTraitement['Date_traitement'] : new \DateTime();


        // Vérifier si le dossier a un Statut_niveau_dossier 'Nouveau' avant de le rejeter
        $Rejete = $entityManager->getRepository(VieDossier::class)
            ->findOneBy([
                'Reference_dossier' => $Reference_dossier,
                'Id_niveau' => $Id_niveau,
                'Statut_niveau_dossier' => 'Nouveau'
            ]);
            if ($Rejete) {
                if($dateDernierTraitement != new \DateTime()){
                $qb = $entityManager->createQueryBuilder();
                // Sélection et mise à jour des éléments existants
                $qb->update('App\Entity\VieDossier', 'vd')
                ->set('vd.Date_traitement', ':dateTraitement')
                ->set('vd.Statut_niveau_dossier', ':statutNiveauDossier')
                ->set('vd.Id_acteur', ':idActeur')
                ->set('vd.Motif_rejet', ':motifRejet')
                ->set('vd.Date_enregistrement', 'vd.Date_enregistrement') 
                ->where('vd.Reference_dossier = :referenceDossier')
                ->andWhere('vd.Id_niveau = :idNiveau')
                ->andWhere("vd.Statut_niveau_dossier = 'Nouveau' ")
                ->setParameter('dateTraitement', new \DateTime())
                ->setParameter('statutNiveauDossier', 'Rejeté')
                ->setParameter('referenceDossier', $Reference_dossier)
                ->setParameter('idNiveau', $Id_niveau)
                ->setParameter('idActeur', $Id_acteur)
                ->setParameter('motifRejet', $Motif_rejet)
                ->getQuery()
                ->execute();
    
                // Création et Enregistrement d'une nouvelle instance de VieDossier
                $nouvelleVieDossier = new VieDossier();
                $nouvelleVieDossier->setReferenceDossier($Reference_dossier);
                $nouvelleVieDossier->setIdNiveau($Id_niveau - 1);
                $nouvelleVieDossier->setIdActeur($Id_acteur);
                $nouvelleVieDossier->setDateEnregistrement($dateEnregistrement);
                $nouvelleVieDossier->setDateEntree(new \DateTime());
                $nouvelleVieDossier->setDateTraitement(new \DateTime());
                $nouvelleVieDossier->setStatutNiveauDossier('Nouveau');
                $entityManager->persist($nouvelleVieDossier);
                $entityManager->flush();
        
                return new JsonResponse(['Message' => 'Rejet réussi']);
                }  
            }
            else{
                return new JsonResponse(['error' => 'Le dossier ne peut être rejeté de suite ou il n\'est pas encore venu à ce niveau'], Response::HTTP_BAD_REQUEST) ;
            } 
    }




                                                     
}
