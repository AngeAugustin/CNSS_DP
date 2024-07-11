<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Entity\VieDossier;
use App\Entity\AffectationDossier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

class AffectationController extends AbstractController
{
    #[Route('/affectations', name: 'multiple_affectations', methods: ['POST'])]
    public function multipleAffectations(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null || !isset($data['Reference_dossiers'])) {
            return new JsonResponse(['error' => 'Invalid or missing data'], Response::HTTP_BAD_REQUEST);
        }

        $referenceDossiers = $data['Reference_dossiers'];
        $idActeur = $data['Id_acteur'] ?? null;
        $idNiveau = $data['Id_niveau'] ?? null;
        $idAffecte = $data['Id_affecte'] ?? null;

        if (!$idActeur || !$idNiveau || !$idAffecte) {
            return new JsonResponse(['error' => 'Missing required parameters'], Response::HTTP_BAD_REQUEST);
        }

        $results = [];
        $errors = [];

        foreach ($referenceDossiers as $referenceDossier) {
            if (!is_string($referenceDossier)) {
                $errors[] = "Invalid reference format for: $referenceDossier";
                continue;
            }

            // Vérification de la dernière affectation
            $lastAffectation = $entityManager->getRepository(AffectationDossier::class)
                ->createQueryBuilder('af')
                ->select('af.Date_affectation, af.Statut_affectation')
                ->where('af.Reference_dossier = :referenceDossier')
                ->setParameter('referenceDossier', $referenceDossier)
                ->orderBy('af.Date_affectation', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

              // Recherche de la date du premier enregistrement 
            $premierEnregistrement = $entityManager->getRepository(VieDossier::class)
            ->createQueryBuilder('vd')
            ->select('vd.Date_enregistrement')
            ->where('vd.Reference_dossier = :referenceDossier')
            ->setParameter('referenceDossier', $referenceDossier)
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
            ->setParameter('referenceDossier', $referenceDossier)
            ->orderBy('vd.Date_traitement', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
            $dateDernierTraitement = $dernierTraitement ? $dernierTraitement['Date_traitement'] : new \DateTime();

            if ($lastAffectation && $lastAffectation['Statut_affectation'] === 'Affecté') {
                $errors[] = "Dossier $referenceDossier is already affected";
                continue;
            }

            // Enregistrement de la nouvelle affectation
            $affectationDossier = new AffectationDossier();
            $affectationDossier->setReferenceDossier($referenceDossier);
            $affectationDossier->setIdAffecteur($idActeur);
            $affectationDossier->setIdAffecte($idAffecte);
            $affectationDossier->setDateAffectation(new \DateTime());
            $affectationDossier->setStatutAffectation('Affecté');
            $affectationDossier->setStatutTraitement('En cours');
            $affectationDossier->setIdNiveau($idNiveau);
            $entityManager->persist($affectationDossier);
            $entityManager->flush();

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
                ->setParameter('statutNiveauDossier', 'Terminé')
                ->setParameter('referenceDossier', $referenceDossier)
                ->setParameter('idNiveau', $idNiveau)
                ->setParameter('idActeur', $idActeur)
                ->getQuery()
                ->execute();
        
                // Création et Enregistrement d'une nouvelle instance de VieDossier
                $nouvelleVieDossier = new VieDossier();
                $nouvelleVieDossier->setReferenceDossier($referenceDossier);
                $nouvelleVieDossier->setIdNiveau($idNiveau + 1);
                $nouvelleVieDossier->setIdActeur($idActeur);
                $nouvelleVieDossier->setDateEnregistrement($dateEnregistrement);
                $nouvelleVieDossier->setDateEntree(new \DateTime());
                $nouvelleVieDossier->setDateTraitement(new \DateTime());
                $nouvelleVieDossier->setStatutNiveauDossier('Nouveau');
                $entityManager->persist($nouvelleVieDossier);
                $entityManager->flush();
                }

            $results[] = [
                'Reference_dossier' => $affectationDossier->getReferenceDossier(),
                'Status' => 'Affecté',
            ];
        }

        return new JsonResponse([
            'Success' => $results,
            'Errors' => $errors,
        ]);
    }
}
