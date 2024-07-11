<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Entity\Acteur;
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

class ListAgentsController extends AbstractController
{
    #[Route('/listagents/{Id_niveau}/{Id_agence}', name: 'listagents', methods: ['GET'])]
    public function Dashboard(EntityManagerInterface $entityManager, Security $security, SessionInterface $session, int $Id_niveau = null, int $Id_agence = null): JsonResponse
    {
            // Query builder
            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder->select('a.Id_acteur', 'a.Username')
            ->from(Acteur::class, 'a')
            ->where('a.Id_niveau = :Id_niveau')
            ->andWhere('a.Id_agence = :Id_agence')
            ->setParameter('Id_agence', $Id_agence)
            ->setParameter('Id_niveau', $Id_niveau + 1);
            $agents = $queryBuilder->getQuery()->getResult();

             // Formatage JSON
             $formattedAgents = [];
             foreach ($agents as $agent) {
                 $formattedAgents[] = [
                     'Id_acteur' => $agent['Id_acteur'],
                     'Username' => $agent['Username'],
                 ];
             }
 
             $response = new JsonResponse($formattedAgents);
             $response->headers->set('Content-Type', 'application/json');
             return $response;

    }
}
