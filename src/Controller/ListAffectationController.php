<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Entity\AffectationDossier;
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

class ListAffectationController extends AbstractController
{
    #[Route('/listAffectation/{Id_niveau}/{Id_acteur}', name: 'listAffectation', methods: ['GET'])]
public function listAffectation(EntityManagerInterface $entityManager, Security $security, SessionInterface $session, int $Id_niveau = null, int $Id_acteur = null): JsonResponse
{
    //Requete avec Query Builder 
    $queryBuilder = $entityManager->createQueryBuilder();
    $queryBuilder->select('a.Reference_dossier', 'a.Id_affecte', 'd.Nom_pensionne', 'd.Prenom_pensionne', 'a.Statut_traitement', 'a.Statut_affectation', 'a.Date_affectation')
        ->from(AffectationDossier::class, 'a')
        ->join('App\Entity\Dossier', 'd', 'WITH', 'a.Reference_dossier = d.Reference_dossier');
    if ($Id_niveau !== null) {
        $queryBuilder->where('a.Id_niveau = :Id_niveau')
            ->setParameter('Id_niveau', $Id_niveau);
    }
    if ($Id_acteur !== null) {
        $queryBuilder->andWhere('a.Id_affecteur = :Id_affecteur')
            ->setParameter('Id_affecteur', $Id_acteur);
    }
    $queryBuilder->andWhere("a.Statut_affectation = 'Affecté' ");
    $allAffectations = $queryBuilder->getQuery()->getResult();


    // Filtrer les doublons en utilisant un tableau associatif
    $uniqueAffectations = [];
    foreach ($allAffectations as $allAffectation) {
        $key = $allAffectation['Reference_dossier'];
        if (!isset($uniqueAffectations[$key]) || in_array($allAffectation['Statut_traitement'], ['En cours', 'Traité'])) {
            $uniqueAffectations[$key] = $allAffectation;
        }
    }

    // Formatage JSON
    $formattedDossiers = [];
    foreach ($uniqueAffectations as $allAffectation) {
        $formattedDossiers[] = [
            'Reference_dossier' => $allAffectation['Reference_dossier'],
            'Nom_pensionne' => $allAffectation['Nom_pensionne'],
            'Prenom_pensionne' => $allAffectation['Prenom_pensionne'],
            'Statut_traitement' => $allAffectation['Statut_traitement'],
            'Statut_affectation' => $allAffectation['Statut_affectation'],
            'Id_affecte' => $allAffectation['Id_affecte'],
            'Date_affectation' => $allAffectation['Date_affectation'] -> format('Y-m-d H:i:s'),
        ];
    }
    $response = new JsonResponse($formattedDossiers);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
    }

}
