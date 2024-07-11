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

class DetailsDossierController extends AbstractController
{
#[Route('/details/{Reference_dossier}', name: 'details', methods: ['GET'])]
public function details(EntityManagerInterface $entityManager, string $Reference_dossier): JsonResponse
{
    // Num_assuré non trouvé
if (!$Reference_dossier) {
    return new JsonResponse(['error' => 'Reference_dossier non trouvé'], Response::HTTP_UNAUTHORIZED);
}

$queryBuilder = $entityManager->createQueryBuilder();
$queryBuilder->select('v.Id_niveau', 'v.Date_entree', 'v.Date_traitement', 'v.Statut_niveau_dossier', 'v.Motif_rejet', 'd.Id_agence')
    ->from(VieDossier::class, 'v')
    ->leftJoin(Dossier::class, 'd', 'WITH', 'v.Reference_dossier = d.Reference_dossier')
    ->where('v.Reference_dossier = :Reference_dossier')
    ->setParameter('Reference_dossier', $Reference_dossier);
$vieDossiers = $queryBuilder->getQuery()->getResult();


$formattedDossierDetails = [];
foreach ($vieDossiers as $vieDossier) {
    if (isset($vieDossier['Id_niveau'], $vieDossier['Date_entree'], $vieDossier['Date_traitement'], $vieDossier['Statut_niveau_dossier'])) {
        $formattedDossierDetails[] = [
            'Id_niveau' => $vieDossier['Id_niveau'],
            'ID_agence' => $vieDossier['Id_agence'],
            'Date_entree' => $vieDossier['Date_entree']->format('Y-m-d H:i:s'),
            'Date_traitement' => $vieDossier['Date_traitement']->format('Y-m-d H:i:s'),
            'Statut_niveau_dossier' => $vieDossier['Statut_niveau_dossier'],
            'Motif_rejet' => $vieDossier['Motif_rejet'],
        ];
    }
}

    $response = new JsonResponse($formattedDossierDetails);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
}
}
