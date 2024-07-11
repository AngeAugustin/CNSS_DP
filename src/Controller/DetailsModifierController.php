<?php

namespace App\Controller;

use App\Entity\Dossier;
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

class DetailsModifierController extends AbstractController
{
    // Détails dossier à modifier //
#[Route('/detailsmodifier/{Reference_dossier}', name: 'detailsmodifier', methods: ['GET'])]
public function detailsmodifier(EntityManagerInterface $entityManager, string $Reference_dossier): JsonResponse
{

    $Reference_dossier = (string)$Reference_dossier;

    if (!$Reference_dossier) {
        return new JsonResponse(['error' => 'Num_assure non trouvé'], Response::HTTP_NOT_FOUND);
    }

$queryBuilder = $entityManager->createQueryBuilder();
$queryBuilder->select('d.Reference_dossier', 'd.Numero_assure', 'd.Numero_pensionne', 'd.Type_dossier', 'd.Nom_pensionne', 'd.Prenom_pensionne', 'd.NPI', 'd.Telephone_pensionne', 'd.Adresse_pensionne')
    ->from(Dossier::class, 'd')
    ->where('d.Reference_dossier = :Reference_dossier')
    ->setParameter('Reference_dossier', $Reference_dossier);
$modifs = $queryBuilder->getQuery()->getResult();


$formattedModifDetails = [];
foreach ($modifs as $modif) {
    if (isset($modif['Nom_pensionne'], $modif['Prenom_pensionne'], $modif['Telephone_pensionne'], $modif['NPI'], $modif['Adresse_pensionne'])) {
        $formattedModifDetails[] = [
            'Reference_dossier' => $modif['Reference_dossier'],
            'Numero_pensionne' => $modif['Numero_pensionne'],
            'Numero_assure' => $modif['Numero_assure'],
            'Nom_pensionne' => $modif['Nom_pensionne'],
            'Prenom_pensionne' => $modif['Prenom_pensionne'],
            'Telephone_pensionne' => $modif['Telephone_pensionne'],
            'NPI' => $modif['NPI'],
            'Adresse_pensionne' => $modif['Adresse_pensionne'],
            'Type_dossier' => $modif['Type_dossier'],
        ];
    }
}

    $response = new JsonResponse($formattedModifDetails);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
}
}
