<?php

namespace App\Controller;

use App\Entity\VieDossier;
use App\Entity\Dossier;
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

class RetraitController extends AbstractController
{
    #[Route('/retraits/{Id_acteur}/{Id_niveau}/{Id_affecte}/{Reference_dossier}', name: 'retraits', methods: ['POST'])]
    public function retraits(Request $request, EntityManagerInterface $entityManager, $Id_acteur, $Id_niveau, $Id_affecte, $Reference_dossier): JsonResponse
    {
        $Id_acteur = (int)$Id_acteur;
        $Id_niveau = (int)$Id_niveau;
        $Id_affecte = (int)$Id_affecte;
        $Reference_dossier = (string)$Reference_dossier;

        if ($Id_niveau === null) {
            return new JsonResponse(['error' => 'Id_niveau non défini'], Response::HTTP_NOT_FOUND);
        }

        if ($Id_affecte === null) {
            return new JsonResponse(['error' => 'Id_affecte non défini'], Response::HTTP_NOT_FOUND);
        }

        if ($Id_affecte === null) {
            return new JsonResponse(['error' => 'Id_affecte non défini'], Response::HTTP_NOT_FOUND);
        }

        if ($Reference_dossier === null) {
            return new JsonResponse(['error' => 'Reference_dossier non défini'], Response::HTTP_NOT_FOUND);
        }

          // Vérification de la dernière affectation
          $lastAffectation = $entityManager->getRepository(AffectationDossier::class)
          ->createQueryBuilder('af')
          ->select('af.Date_affectation, af.Statut_affectation')
          ->where('af.Reference_dossier = :referenceDossier')
          ->setParameter('referenceDossier', $Reference_dossier)
          ->orderBy('af.Date_affectation', 'DESC')
          ->setMaxResults(1)
          ->getQuery()
          ->getOneOrNullResult();

        // Recherche de la date du premier enregistrement 
        $premierEnregistrement = $entityManager->getRepository(VieDossier::class)
        ->createQueryBuilder('vd')
        ->select('vd.Date_enregistrement')
        ->where('vd.Reference_dossier = :referenceDossier')
        ->setParameter('referenceDossier', $Reference_dossier)
        ->orderBy('vd.Date_enregistrement', 'ASC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
        $dateEnregistrement = $premierEnregistrement ? $premierEnregistrement['Date_enregistrement'] : new \DateTime();
        
        // Recherche de la dernière date de traitement pour ce pensionne
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

        $Retire = $entityManager->getRepository(AffectationDossier::class)
        ->findOneBy([
            'Reference_dossier' => $Reference_dossier,
            'Statut_affectation' => 'Retiré',
        ]);

        if(!$Retire){
            if($dateDernierTraitement != new \DateTime()){
                $qb = $entityManager->createQueryBuilder();
                // Sélection et mise à jour des éléments existants
                $qb->update('App\Entity\VieDossier', 'vd')
                ->set('vd.Date_traitement', ':dateTraitement')
                ->set('vd.Statut_niveau_dossier', ':statutNiveauDossier')
                ->set('vd.Id_acteur', ':idActeur')
                ->set('vd.Date_enregistrement', 'vd.Date_enregistrement') 
                ->where('vd.Reference_dossier = :referenceDossier')
                ->andWhere('vd.Id_niveau = :idNiveau')
                ->andWhere("vd.Statut_niveau_dossier = 'Nouveau' ")
                ->setParameter('dateTraitement', new \DateTime())
                ->setParameter('statutNiveauDossier', 'Retiré')
                ->setParameter('referenceDossier', $Reference_dossier)
                ->setParameter('idNiveau', $Id_niveau + 1)
                ->setParameter('idActeur', $Id_acteur)
                ->getQuery()
                ->execute();
        
                // Création et Enregistrement d'une nouvelle instance de VieDossier
                $nouvelleVieDossier = new VieDossier();
                $nouvelleVieDossier->setReferenceDossier($Reference_dossier);
                $nouvelleVieDossier->setIdNiveau($Id_niveau);
                $nouvelleVieDossier->setIdActeur($Id_acteur);
                $nouvelleVieDossier->setDateEnregistrement($dateEnregistrement);
                $nouvelleVieDossier->setDateEntree(new \DateTime());
                $nouvelleVieDossier->setDateTraitement(new \DateTime());
                $nouvelleVieDossier->setStatutNiveauDossier('Nouveau');
                $entityManager->persist($nouvelleVieDossier);
                $entityManager->flush();
                }

                // Enregistrement de la nouvelle affectation
                $affectationDossier = new AffectationDossier();
                $affectationDossier->setReferenceDossier($Reference_dossier);
                $affectationDossier->setIdAffecteur($Id_acteur);
                $affectationDossier->setIdAffecte($Id_affecte);
                $affectationDossier->setDateAffectation(new \DateTime());
                $affectationDossier->setStatutAffectation('Retiré');
                $affectationDossier->setStatutTraitement('Non Traité');
                $affectationDossier->setIdNiveau($Id_niveau);
                $entityManager->persist($affectationDossier);
                $entityManager->flush();

                return new JsonResponse(['Message' => 'Retrait réussi']);
        }
        else{
            return new JsonResponse(['error' => 'Dossier retiré auparavant'], Response::HTTP_BAD_REQUEST) ;
        }
    }
}
