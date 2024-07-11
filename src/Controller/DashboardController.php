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

class DashboardController extends AbstractController
{
#[Route('/dashboard/{Id_niveau}/{Id_agence}', name: 'Dashboard', methods: ['GET'])]
public function Dashboard(EntityManagerInterface $entityManager, Security $security, SessionInterface $session, int $Id_niveau = null, int $Id_agence = null): JsonResponse
{
            if ($Id_niveau == 1 || $Id_niveau == 2 || $Id_niveau == 3 ){
            //Requete avec Query Builder avec Id_niveau == 1
            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder->select('d.Reference_dossier', 'd.Numero_pensionne', 'd.Id_agence', 'd.Nom_pensionne', 'd.Prenom_pensionne', 'd.Statut_global_dossier', 'v.Statut_niveau_dossier', 'v.Date_enregistrement')
                ->from(Dossier::class, 'd')
                ->join('App\Entity\VieDossier', 'v', 'WITH', 'd.Reference_dossier = v.Reference_dossier');
            if ($Id_niveau !== null) {
                $queryBuilder->where('v.Id_niveau = :Id_niveau')
                    ->setParameter('Id_niveau', $Id_niveau);
            }
            if ($Id_agence !== null) {
                $queryBuilder->andWhere('d.Id_agence = :Id_agence')
                    ->setParameter('Id_agence', $Id_agence);
            }
            $queryBuilder->orderBy('v.Statut_niveau_dossier', 'DESC');
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
            $formattedDossiers = [];
            foreach ($uniqueDossiers as $dossier) {
                $formattedDossiers[] = [
                    'Date_enregistrement' => $dossier['Date_enregistrement']->format('Y-m-d H:i:s'),
                    'Reference_dossier' => $dossier['Reference_dossier'],
                    'Numero_pensionne' => $dossier['Numero_pensionne'],
                    'Nom_pensionne' => $dossier['Nom_pensionne'],
                    'Prenom_pensionne' => $dossier['Prenom_pensionne'],
                    'Statut_niveau_dossier' => $dossier['Statut_niveau_dossier'],
                    'Statut_global_dossier' => $dossier['Statut_global_dossier'],
                    'ID_agence' => $dossier['Id_agence'],
                ];
            }

            $response = new JsonResponse($formattedDossiers);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
            }
            else{
            //Requete avec Query Builder si Id_niveau != 1
            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder->select('d.Reference_dossier', 'd.Nom_pensionne', 'd.Id_agence', 'd.Numero_pensionne', 'd.Prenom_pensionne', 'd.Statut_global_dossier', 'v.Statut_niveau_dossier', 'v.Date_enregistrement')
                ->from(Dossier::class, 'd')
                ->join('App\Entity\VieDossier', 'v', 'WITH', 'd.Reference_dossier = v.Reference_dossier');
            if ($Id_niveau !== null) {
                $queryBuilder->where('v.Id_niveau = :Id_niveau')
                    ->setParameter('Id_niveau', $Id_niveau);
            }
            $queryBuilder->orderBy('v.Statut_niveau_dossier', 'DESC');
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
            $formattedDossiers = [];
            foreach ($uniqueDossiers as $dossier) {
                $formattedDossiers[] = [
                    'Date_enregistrement' => $dossier['Date_enregistrement']->format('Y-m-d H:i:s'),
                    'Reference_dossier' => $dossier['Reference_dossier'],
                    'Numero_pensionne' => $dossier['Numero_pensionne'],
                    'Nom_pensionne' => $dossier['Nom_pensionne'],
                    'Prenom_pensionne' => $dossier['Prenom_pensionne'],
                    'Statut_niveau_dossier' => $dossier['Statut_niveau_dossier'],
                    'Statut_global_dossier' => $dossier['Statut_global_dossier'],
                    'ID_agence' => $dossier['Id_agence'],
                ];
            }
            $response = new JsonResponse($formattedDossiers);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
            }
            
}
}
