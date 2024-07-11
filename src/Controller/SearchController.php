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

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search', methods: ['GET'])]
public function search(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    // Récupération des paramètres de la requête
    $criteria = $request->query->all();

    $queryBuilder = $entityManager->createQueryBuilder();
    $queryBuilder->select('v.Nom_pensionne', 'v.Prenom_pensionne', 'v.Numero_pensionne', 'v.Reference_dossier', 'v.NPI', 'v.Id_agence', 'v.Statut_global_dossier')
        ->from(Dossier::class, 'v')
        ->where($queryBuilder->expr()->orX(
            $queryBuilder->expr()->eq('v.Statut_global_dossier', ':statutglobal_en_cours'),
            $queryBuilder->expr()->eq('v.Statut_global_dossier', ':statutglobal_termine')
        ))
        ->setParameter('statutglobal_en_cours', 'En cours')
        ->setParameter('statutglobal_termine', 'Terminé');

    // Construction de la clause WHERE en fonction des critères fournis
    $whereClause = [];
    $parameters = [];

    foreach ($criteria as $key => $value) {
        switch ($key) {
            case 'Nom_pensionne':
                $whereClause[] = 'v.Nom_pensionne LIKE :Nom_pensionne';
                $parameters['Nom_pensionne'] = '%' . $value . '%';
                break;
             case 'Prenom_pensionne':
                $whereClause[] = 'v.Prenom_pensionne LIKE :Prenom_pensionne';
                $parameters['Prenom_pensionne'] = '%' . $value . '%';
                break;
            case 'Numero_pensionne':
                $whereClause[] = 'v.Numero_pensionne LIKE :Numero_pensionne';
                $parameters['Numero_pensionne'] = '%' . $value . '%';
                break;
            case 'Reference_dossier':
                $whereClause[] = 'v.Reference_dossier LIKE :Reference_dossier';
                $parameters['Reference_dossier'] = '%' . $value . '%';
                break;
            case 'NPI':
                $whereClause[] = 'v.NPI LIKE :NPI';
                $parameters['NPI'] = '%' . $value . '%';
                break;
            case 'Id_agence':
                $whereClause[] = 'v.Id_agence LIKE :Id_agence';
                $parameters['Id_agence'] = '%' . $value . '%';
                break;
            default:
                // Ignorer les clés inconnues
                break;
        }
    }

    // Si aucun critère n'est fourni, afficher tous les dossiers
    if (empty($whereClause)) {
        $queryBuilder->andWhere('1 = 1');
    } else {
        $queryBuilder->andWhere(implode(' OR ', $whereClause));
        foreach ($parameters as $key => $value) {
            $queryBuilder->setParameter($key, $value);
        }
    }

    // Exécution de la requête
    $consultations = $queryBuilder->getQuery()->getResult();

    // Formatage des résultats
    $formattedConsultation = [];
    foreach ($consultations as $consultation) {
        $formattedConsultation[] = [
            'Nom_pensionne' => $consultation['Nom_pensionne'],
            'Prenom_pensionne' => $consultation['Prenom_pensionne'],
            'Numero_pensionne' => $consultation['Numero_pensionne'],
            'Reference_dossier' => $consultation['Reference_dossier'],
            'NPI' => $consultation['NPI'],
            'ID_agence' => $consultation['Id_agence'],
            'Statut_global_dossier' => $consultation['Statut_global_dossier'],
        ];
    }

    // Création de la réponse JSON
    $response = new JsonResponse($formattedConsultation);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
}


}
