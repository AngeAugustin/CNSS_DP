<?php

namespace App\Controller;

use App\Entity\Dossier;
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


class AnnulerController extends AbstractController
{
   #[Route('/annuler/{Reference_dossier}', name: 'annuler', methods: ['POST'])]
   public function annuler(Request $request, $Reference_dossier, EntityManagerInterface $entityManager): JsonResponse
   {
       $Reference_dossier = (string)$Reference_dossier;

       // Num_assure 
       if (!$Reference_dossier) {
           return new JsonResponse(['error' => 'Reference_dossier non trouvé'], Response::HTTP_NOT_FOUND);
       }

       $Annule = $entityManager->getRepository(Dossier::class)
           ->findOneBy([
               'Reference_dossier' => $Reference_dossier,
           ]);
           if ($Annule) {
               $qb = $entityManager->createQueryBuilder();     
               // Suppression des éléments existants
               $qb->delete('App\Entity\Dossier', 'd')
                   ->where('d.Reference_dossier = :referenceDossier')
                   ->setParameter('referenceDossier', $Reference_dossier)
                   ->getQuery()
                   ->execute();
               return new JsonResponse(['Message' => 'Annulation réussie']);
           }
           else{
               return new JsonResponse(['error' => 'Le dossier ne peut être annulé. Etat d\'incompatibilité'], Response::HTTP_BAD_REQUEST) ;
           } 
   }

}
