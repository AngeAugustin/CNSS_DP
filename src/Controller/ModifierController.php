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

class ModifierController extends AbstractController
{
    // Modifier Dossier //
#[Route('/modifier/{Reference_dossier}', name: 'modifier', methods: ['POST'])]
public function modifier(Request $request, $Reference_dossier, EntityManagerInterface $entityManager): JsonResponse
{
    $Reference_dossier = (string)$Reference_dossier;

    // Vérification si le dossier existe
    $dossier = $entityManager->getRepository(Dossier::class)->findOneBy(['Reference_dossier' => $Reference_dossier]);

    if (!$dossier) {
        return new JsonResponse(['error' => 'Dossier non trouvé'], Response::HTTP_NOT_FOUND);
    }

    $Modifier = $entityManager->getRepository(Dossier::class)
    ->findOneBy([
        'Reference_dossier' => $Reference_dossier,
    ]);

    // Récupération des données du formulaire
    $data = json_decode($request->getContent(), true);

    // Mise à jour des champs du dossier
    if ($Modifier){
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->update('App\Entity\Dossier', 'd')
        ->set('d.Nom_pensionne', ':Nom_pensionne')
        ->set('d.Type_dossier', ':Type_dossier')
        ->set('d.Numero_pensionne', ':Numero_pensionne')
        ->set('d.Prenom_pensionne', ':Prenom_pensionne')
        ->set('d.NPI', ':NPI')
        ->set('d.Numero_assure', ':Numero_assure')
        ->set('d.Telephone_pensionne', ':Telephone_pensionne')
        ->set('d.Adresse_pensionne', ':Adresse_pensionne')
        ->where('d.Reference_dossier = :referenceDossier')
        ->setParameter('referenceDossier', $Reference_dossier)
        ->setParameter('Nom_pensionne', $data['Nom_pensionne'])
        ->setParameter('Numero_pensionne', $data['Numero_pensionne'])
        ->setParameter('Type_dossier', $data['Type_dossier'])
        ->setParameter('Prenom_pensionne', $data['Prenom_pensionne'])
        ->setParameter('Telephone_pensionne', $data['Telephone_pensionne'])
        ->setParameter('Numero_assure', $data['Numero_assure'])
        ->setParameter('Adresse_pensionne', $data['Adresse_pensionne'])
        ->setParameter('NPI', $data['NPI'])
        ->getQuery()
        ->execute();

    // Retourner une réponse de succès
    return new JsonResponse(['Message' => 'Dossier mise à jour avec succès'], Response::HTTP_OK);
    }
    else{
        return new JsonResponse(['error' => 'Erreur lors de la mise à jour'], Response::HTTP_UNAUTHORIZED) ;
    }
    
}
        
}
