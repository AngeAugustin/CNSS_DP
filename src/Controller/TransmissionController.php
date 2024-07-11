<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Entity\VieDossier;
use App\Entity\Bordereau;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

class TransmissionController extends AbstractController
{
    #[Route('/transmissions', name: 'multiple_transmissions', methods: ['POST'])]
    public function multipleTransmissions(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null || !isset($data['Reference_dossiers'])) {
            return new JsonResponse(['error' => 'Invalid or missing data'], Response::HTTP_BAD_REQUEST);
        }

        $referenceDossiers = $data['Reference_dossiers'];
        $idExpediteur = $data['Id_niveau'] ?? null;

        if (!$idExpediteur) {
            return new JsonResponse(['error' => 'Missing required parameters'], Response::HTTP_BAD_REQUEST);
        }

        $results = [];
        $errors = [];

        foreach ($referenceDossiers as $referenceDossier) {
            if (!is_string($referenceDossier)) {
                $errors[] = "Invalid reference format for: $referenceDossier";
                continue;
            }

            $Transmission = $entityManager->getRepository(Bordereau::class)
             ->findOneBy([
             'Reference_dossier' => $referenceDossier,
             'Statut_transmission' => 'Transmis',
         ]);

            if ($Transmission) {
                $errors[] = "Dossier $referenceDossier is already trans";
                continue;
            }
            else{
            $qb = $entityManager->createQueryBuilder();
            $qb->update('App\Entity\Bordereau', 'b')
            ->set('b.Statut_transmission', ':Statut_transmission')
            ->where('b.Reference_dossier = :referenceDossier')
            ->andWhere('b.Id_expediteur = :Id_expediteur')
            ->andWhere("b.Statut_transmission = 'A transmettre' ")
            ->setParameter('referenceDossier', $referenceDossier)
            ->setParameter('Statut_transmission', 'Transmis')
            ->setParameter('Id_expediteur', $idExpediteur)
            ->getQuery()
            ->execute();

            $results[] = [
                'Reference_dossier' => $referenceDossier,
                'Status' => 'Transmis',
            ];
            }
        }

        return new JsonResponse([
            'Success' => $results,
            'Errors' => $errors,
        ]);
    }
}
