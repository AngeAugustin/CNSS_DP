<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Entity\VieDossier;
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

class ListSearchController extends AbstractController
{
    // Suivi Dossier Pensionné //
#[Route('/listSearch/{Reference_dossier}', name: 'listSearch', methods: ['GET'])]
public function listSearch(EntityManagerInterface $entityManager, string $Reference_dossier): JsonResponse
{
    // Vérifie si la référence de dossier est vide
    if (!$Reference_dossier) {
        return new JsonResponse(['error' => 'Référence de dossier non fournie'], Response::HTTP_UNAUTHORIZED);
    }

    $queryBuilder = $entityManager->createQueryBuilder();
    $queryBuilder->select('d.Numero_pensionne', 'd.Nom_pensionne', 'd.Prenom_pensionne', 'd.Id_agence', 'd.Reference_dossier','v.Id_niveau', 'v.Date_entree', 'v.Date_traitement', 'v.Statut_niveau_dossier', 'v.Motif_rejet')
        ->from(Dossier::class, 'd')
        ->join('App\Entity\VieDossier', 'v', 'WITH', 'd.Reference_dossier = v.Reference_dossier')
        ->where('d.Reference_dossier = :Reference_dossier')
        ->setParameter('Reference_dossier', $Reference_dossier);
    $listSearchs = $queryBuilder->getQuery()->getResult(); 

    $formattedlistSearchs = [];
    foreach ($listSearchs as $listSearch){
        $formattedlistSearchs[] = [
            'Numero_pensionne' => $listSearch['Numero_pensionne'],
            'Nom_pensionne' => $listSearch['Nom_pensionne'],
            'Prenom_pensionne' => $listSearch['Prenom_pensionne'],
            'Reference_dossier' => $listSearch['Reference_dossier'],
            'Id_niveau' => $listSearch['Id_niveau'],
            'ID_agence' => $listSearch['Id_agence'],
            'Date_entree' => $listSearch['Date_entree']->format('Y-m-d '),
            'Date_traitement' => $listSearch['Date_traitement']->format('Y-m-d '),
            'Statut_niveau_dossier' => $listSearch['Statut_niveau_dossier'],
            'Motif_rejet' => $listSearch['Motif_rejet'],
        ];
    }
    
    $response = new JsonResponse($formattedDossierPensionne);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
}
}
