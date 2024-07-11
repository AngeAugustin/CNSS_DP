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

class StatsController extends AbstractController
{
    #[Route('/stats', name: 'stats', methods: ['GET'])]
public function stats(EntityManagerInterface $entityManager, Security $security, SessionInterface $session): JsonResponse
{
            // Query builder
            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder->select('d.Reference_dossier', 'd.Numero_pensionne', 'd.Nom_pensionne', 'd.Prenom_pensionne', 'd.Statut_global_dossier','v.Date_entree',
            'v.Date_traitement','v.Id_niveau','v.Id_acteur','v.Motif_rejet', 'v.Statut_niveau_dossier','v.Date_enregistrement')
                ->from(Dossier::class, 'd')
                ->join('App\Entity\VieDossier', 'v', 'WITH', 'd.Reference_dossier = v.Reference_dossier')
                ->orderBy('d.Statut_global_dossier', 'DESC');
            $dossiers = $queryBuilder->getQuery()->getResult();
            

            // Filtrer les doublons en utilisant un tableau associatif
            $uniqueDossiers = [];
            foreach ($dossiers as $dossier) {
                $key = $dossier['Reference_dossier'];
                // Si le dossier n'existe pas déjà ou s'il a un statut "Nouveau" ou "Terminé", l'ajouter ou le remplacer
                if (!isset($uniqueDossiers[$key]) || in_array($dossier['Statut_niveau_dossier'], ['Nouveau', 'Terminé'])) {
                    $uniqueDossiers[$key] = $dossier;
                }
            }
           
    // Formatage JSON
    $formattedChartDossiers = [];
    foreach ($uniqueDossiers as $dossier) {
        $formattedChartDossiers[] = [
            'Reference_dossier' => $dossier['Reference_dossier'],
            'Numero_pensionne' => $dossier['Numero_pensionne'],
            'Nom_pensionne' => $dossier['Nom_pensionne'],
            'Prenom_pensionne' => $dossier['Prenom_pensionne'],
            'Statut_niveau_dossier' => $dossier['Statut_niveau_dossier'],
            'Statut_global_dossier' => $dossier['Statut_global_dossier'],
            'Date_enregistrement' => $dossier['Date_enregistrement']->format('Y-m-d H:i:s'),
            'Date_traitement' => $dossier['Date_traitement']->format('Y-m-d H:i:s'),
            'Date_entree' => $dossier['Date_entree']->format('Y-m-d H:i:s'),
            'Id_acteur' => $dossier['Id_acteur'],
            'Id_niveau' => $dossier['Id_niveau'],
            'Motif_rejet' => $dossier['Motif_rejet'],
        ];
    }

    $response = new JsonResponse($formattedChartDossiers);
    $response->headers->set('Content-Type', 'application/json');
    return $response; 
}
}
