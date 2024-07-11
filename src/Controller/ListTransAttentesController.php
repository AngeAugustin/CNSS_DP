<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Entity\VieDossier;
use App\Entity\Bordereau;
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

class ListTransAttentesController extends AbstractController
{
    #[Route('/transattentes/{Id_niveau}/{Id_agence}', name: 'transattentes', methods: ['GET'])]
    public function transattentes(EntityManagerInterface $entityManager, SessionInterface $session, int $Id_niveau = null, int $Id_agence = null): JsonResponse
    {
        // QueryBuilder
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('b.Reference_dossier', 'd.Numero_pensionne', 'b.Id_destinataire', 'b.Id_agence_destination', 'b.Statut_transmission')
            ->from(Bordereau::class, 'b')
            ->join('App\Entity\Dossier', 'd', 'WITH', 'b.Reference_dossier = d.Reference_dossier')
            ->where('b.Id_expediteur = :Id_expediteur')
            ->andWhere('b.Id_agence_expedition = :Id_agence_expedition')
            ->andWhere("b.Statut_transmission = 'A transmettre' ")
            ->setParameter('Id_expediteur', $Id_niveau)
            ->setParameter('Id_agence_expedition', $Id_agence);
        $transmissions = $queryBuilder->getQuery()->getResult();

        $formattedTransmissions = [];
        foreach ($transmissions as $transmission) {
            if (isset($transmission['Reference_dossier'], $transmission['Numero_pensionne'], $transmission['Id_destinataire'], $transmission['Id_agence_destination'], $transmission['Statut_transmission'])) {
                $formattedTransmissions[] = [
                    'Numero_pensionne' => $transmission['Numero_pensionne'],
                    'Reference_dossier' => $transmission['Reference_dossier'],
                    'Id_destinataire' => $transmission['Id_destinataire'],
                    'Id_agence_destination' => $transmission['Id_agence_destination'],
                    'Statut_transmission' => $transmission['Statut_transmission'],
                ];
            }
        }

            $response = new JsonResponse($formattedTransmissions);
            $response->headers->set('Content-Type', 'application/json');
            return $response;

    }
}

