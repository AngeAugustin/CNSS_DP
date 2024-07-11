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

class ListRetraitsController extends AbstractController
{
    #[Route('/listRetrait/{Id_niveau}/{Id_acteur}', name: 'listRetrait', methods: ['GET'])]
public function listRetrait(EntityManagerInterface $entityManager, Security $security, SessionInterface $session, int $Id_niveau = null, int $Id_acteur = null): JsonResponse
{
    //Requete avec Query Builder 
    $queryBuilder = $entityManager->createQueryBuilder();
    $queryBuilder->select('a.Reference_dossier', 'a.Id_affecte', 'd.Nom_pensionne', 'd.Prenom_pensionne', 'a.Statut_traitement', 'a.Statut_affectation', 'a.Date_retrait')
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
    $queryBuilder->andWhere("a.Statut_affectation = 'Retiré' ");
    $allRetraits = $queryBuilder->getQuery()->getResult();


    // Filtrer les doublons en utilisant un tableau associatif
    $uniqueRetraits = [];
    foreach ($allRetraits as $allRetrait) {
        $key = $allRetrait['Reference_dossier'];
        if (!isset($uniqueRetraits[$key]) || in_array($allRetrait['Statut_traitement'], ['En cours', 'Traité'])) {
            $uniqueRetraits[$key] = $allRetrait;
        }
    }

    // Formatage JSON
    $formattedDossiers = [];
    foreach ($uniqueRetraits as $allRetrait) {
        $formattedDossiers[] = [
            'Reference_dossier' => $allRetrait['Reference_dossier'],
            'Nom_pensionne' => $allRetrait['Nom_pensionne'],
            'Prenom_pensionne' => $allRetrait['Prenom_pensionne'],
            'Statut_traitement' => $allRetrait['Statut_traitement'],
            'Statut_affectation' => $allRetrait['Statut_affectation'],
            'Id_affecte' => $allRetrait['Id_affecte'],
            'Date_retrait' => $allRetrait['Date_retrait'] -> format('Y-m-d H:i:s'),
        ];
    }
    $response = new JsonResponse($formattedDossiers);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
    }

}
