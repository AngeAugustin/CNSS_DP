<?php

namespace App\Controller;

use App\Entity\VieDossier;
use App\Entity\Dossier;
use App\Entity\AffectationDossier;
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

class ValidationController extends AbstractController
{
     // Valider Dossier //
 #[Route('/valider/{Reference_dossier}/{Id_niveau}/{Id_acteur}', name: 'validation', methods: ['POST'])]
 public function validation(Request $request, $Reference_dossier, $Id_niveau, $Id_acteur, EntityManagerInterface $entityManager): JsonResponse
 {
     $Reference_dossier = (string)$Reference_dossier;

     // Num_assure 
     if (!$Reference_dossier) {
         return new JsonResponse(['error' => 'Reference_dossier non fourni'], Response::HTTP_NOT_FOUND);
     }
     // Id_niveau 
     if (!$Id_niveau) {
         return new JsonResponse(['error' => 'Id_niveau non fourni'], Response::HTTP_NOT_FOUND);
     }
      // Id_acteur
      if (!$Id_acteur) {
         return new JsonResponse(['error' => 'Id_acteur non fourni'], Response::HTTP_NOT_FOUND);
     }
     

      // Recherche de la date du premier enregistrement pour ce Num_assure
      $premierEnregistrement = $entityManager->getRepository(VieDossier::class)
      ->createQueryBuilder('vd')
      ->select('vd.Date_enregistrement')
      ->where('vd.Reference_dossier = :referenceDossier')
      ->setParameter('referenceDossier', $Reference_dossier)
      ->orderBy('vd.Date_enregistrement', 'ASC')
      ->setMaxResults(1)
      ->getQuery()
      ->getOneOrNullResult();
      $dateEnregistrement = $premierEnregistrement ? $premierEnregistrement['Date_enregistrement'] : new \DateTime();

      
      $IDAgence = $entityManager->getRepository(Dossier::class)
      ->createQueryBuilder('d')
      ->select('d.Id_agence')
      ->where('d.Reference_dossier = :referenceDossier')
      ->setParameter('referenceDossier', $Reference_dossier)
      ->getQuery()
      ->getSingleScalarResult(); 

      // Recherche de la dernière date de traitement pour ce Num_assure
      $dernierTraitement = $entityManager->getRepository(VieDossier::class)
      ->createQueryBuilder('vd')
      ->select('vd.Date_traitement')
      ->where('vd.Reference_dossier = :referenceDossier')
      ->setParameter('referenceDossier', $Reference_dossier)
      ->orderBy('vd.Date_traitement', 'DESC')
      ->setMaxResults(1)
      ->getQuery()
      ->getOneOrNullResult();
      $dateDernierTraitement = $dernierTraitement ? $dernierTraitement['Date_traitement'] : new \DateTime();

     // Vérifier si le dossier a Statut_niveau_dossier = 'Nouveau' avant de le valider 
     $Valide = $entityManager->getRepository(VieDossier::class)
         ->findOneBy([
             'Reference_dossier' => $Reference_dossier,
             'Id_niveau' => $Id_niveau,
             'Statut_niveau_dossier' => 'Nouveau',
         ]);
     if ($Valide) {
         if($dateDernierTraitement != new \DateTime()){
             if ($Id_niveau == 13){
                     // Transmission Direction Générale vers Agence
                     $qb = $entityManager->createQueryBuilder();
                     // Sélection et mise à jour des éléments existants
                     $qb->update('App\Entity\VieDossier', 'vvd')
                     ->set('vvd.Date_traitement', ':dateTraitement')
                     ->set('vvd.Statut_niveau_dossier', ':statutNiveauDossier')
                     ->set('vvd.Id_acteur', ':idActeur')
                     ->set('vvd.Date_enregistrement', 'vvd.Date_enregistrement') 
                     ->where('vvd.Reference_dossier = :referenceDossier')
                     ->andWhere('vvd.Id_niveau = :idNiveau')
                     ->andWhere("vvd.Statut_niveau_dossier = 'Nouveau' ")
                     ->setParameter('dateTraitement', new \DateTime())
                     ->setParameter('statutNiveauDossier', 'Terminé')
                     ->setParameter('referenceDossier', $Reference_dossier)
                     ->setParameter('idNiveau', $Id_niveau)
                     ->setParameter('idActeur', $Id_acteur)
                     ->getQuery()
                     ->execute();

                     $qb13 = $entityManager->createQueryBuilder();
                     // Sélection et mise à jour des éléments existants
                     $qb13->update('App\Entity\Dossier', 'd')
                     ->set('d.Statut_global_dossier', ':statutGlobal')
                     ->where('d.Reference_dossier = :referenceDossier')
                     ->setParameter('statutGlobal', 'Terminé')
                     ->setParameter('referenceDossier', $Reference_dossier)
                     ->getQuery()
                     ->execute();

                     // Création et Enregistrement d'une nouvelle instance de Transmission de bordereau
                     $transmission = new Bordereau();
                     $transmission->setIdExpediteur($Id_niveau);
                     $transmission->setIdDestinataire($Id_niveau + 1);
                     $transmission->setIdAgenceExpedition(1);
                     $transmission->setIdAgenceDestination($IDAgence); // Id_agence qui a enregistré le dossier 
                     $transmission->setReferenceDossier($Reference_dossier);
                     $transmission->setDateTransmission(new \DateTime());
                     $transmission->setStatutTransmission('A transmettre');
                     $entityManager->persist($transmission);
                     $entityManager->flush();

                     return new JsonResponse(['Message' => 'Validation réussie']);
                 }
                 else{
                     if($Id_niveau == 1){
                     // Transmision au sein de l'Agence
                     $qb = $entityManager->createQueryBuilder();
                     // Sélection et mise à jour des éléments existants
                     $qb->update('App\Entity\VieDossier', 'vd')
                     ->set('vd.Date_traitement', ':dateTraitement')
                     ->set('vd.Statut_niveau_dossier', ':statutNiveauDossier')
                     ->set('vd.Id_acteur', ':idActeur')
                     ->set('vd.Date_enregistrement', 'vd.Date_enregistrement') 
                     ->where('vd.Reference_dossier = :referenceDossier')
                     ->andWhere('vd.Id_niveau = :idNiveau')
                     ->andWhere("vd.Statut_niveau_dossier = 'Nouveau' ")
                     ->setParameter('dateTraitement', new \DateTime())
                     ->setParameter('statutNiveauDossier', 'Terminé')
                     ->setParameter('referenceDossier', $Reference_dossier)
                     ->setParameter('idNiveau', $Id_niveau)
                     ->setParameter('idActeur', $Id_acteur)
                     ->getQuery()
                     ->execute();

                     $qb1 = $entityManager->createQueryBuilder();
                     // Sélection et mise à jour des éléments existants
                     $qb1->update('App\Entity\Dossier', 'd')
                     ->set('d.Statut_global_dossier', ':statutGlobal')
                     ->where('d.Reference_dossier = :referenceDossier')
                     ->setParameter('statutGlobal', 'En cours')
                     ->setParameter('referenceDossier', $Reference_dossier)
                     ->getQuery()
                     ->execute();

                     // Création et Enregistrement d'une nouvelle instance de VieDossier
                     $nouvelleVieDossier = new VieDossier();
                     $nouvelleVieDossier->setReferenceDossier($Reference_dossier);
                     $nouvelleVieDossier->setIdNiveau($Id_niveau + 1);
                     $nouvelleVieDossier->setIdActeur($Id_acteur);
                     $nouvelleVieDossier->setDateEnregistrement($dateEnregistrement);
                     $nouvelleVieDossier->setDateEntree(new \DateTime());
                     $nouvelleVieDossier->setDateTraitement(new \DateTime());
                     $nouvelleVieDossier->setStatutNiveauDossier('Nouveau');
                     $entityManager->persist($nouvelleVieDossier);
                     $entityManager->flush();

                     // Création et Enregistrement d'une nouvelle instance de Transmission de bordereau
                     $transmission = new Bordereau();
                     $transmission->setIdExpediteur($Id_niveau);
                     $transmission->setIdDestinataire($Id_niveau + 1);
                     $transmission->setIdAgenceExpedition($IDAgence);
                     $transmission->setIdAgenceDestination($IDAgence); // Id_agence qui a enregistré le dossier 
                     $transmission->setReferenceDossier($Reference_dossier);
                     $transmission->setDateTransmission(new \DateTime());
                     $transmission->setStatutTransmission('A transmettre');
                     $entityManager->persist($transmission);
                     $entityManager->flush();

                     return new JsonResponse(['Message' => 'Validation réussie']);
                     }
                     else{
                        if ($Id_niveau == 3 ){
                            // Niveau 3  Transmission Agence vers Direction Générale
                            $qb = $entityManager->createQueryBuilder();
                            // Sélection et mise à jour des éléments existants
                            $qb->update('App\Entity\VieDossier', 'vd')
                            ->set('vd.Date_traitement', ':dateTraitement')
                            ->set('vd.Statut_niveau_dossier', ':statutNiveauDossier')
                            ->set('vd.Id_acteur', ':idActeur')
                            ->set('vd.Date_enregistrement', 'vd.Date_enregistrement') 
                            ->where('vd.Reference_dossier = :referenceDossier')
                            ->andWhere('vd.Id_niveau = :idNiveau')
                            ->andWhere("vd.Statut_niveau_dossier = 'Nouveau' ")
                            ->setParameter('dateTraitement', new \DateTime())
                            ->setParameter('statutNiveauDossier', 'Terminé')
                            ->setParameter('referenceDossier', $Reference_dossier)
                            ->setParameter('idNiveau', $Id_niveau)
                            ->setParameter('idActeur', $Id_acteur)
                            ->getQuery()
                            ->execute();

                            // Création et Enregistrement d'une nouvelle instance de VieDossier
                            $nouvelleVieDossier = new VieDossier();
                            $nouvelleVieDossier->setReferenceDossier($Reference_dossier);
                            $nouvelleVieDossier->setIdNiveau($Id_niveau + 1);
                            $nouvelleVieDossier->setIdActeur($Id_acteur);
                            $nouvelleVieDossier->setDateEnregistrement($dateEnregistrement);
                            $nouvelleVieDossier->setDateEntree(new \DateTime());
                            $nouvelleVieDossier->setDateTraitement(new \DateTime());
                            $nouvelleVieDossier->setStatutNiveauDossier('Nouveau');
                            $entityManager->persist($nouvelleVieDossier);
                            $entityManager->flush();

                            // Création et Enregistrement d'une nouvelle instance de Transmission de bordereau
                            $transmission = new Bordereau();
                            $transmission->setIdExpediteur($Id_niveau);
                            $transmission->setIdDestinataire($Id_niveau + 1);
                            $transmission->setIdAgenceExpedition($IDAgence);
                            $transmission->setIdAgenceDestination(1);
                            $transmission->setReferenceDossier($Reference_dossier);
                            $transmission->setDateTransmission(new \DateTime());
                            $transmission->setStatutTransmission('A transmettre');
                            $entityManager->persist($transmission);
                            $entityManager->flush();

                            return new JsonResponse(['Message' => 'Validation réussie']);
                        }
                        else{
                     //Tous les autres niveaux //    Transmission au sein de la Direction Générale
                     $qb = $entityManager->createQueryBuilder();
                     // Sélection et mise à jour des éléments existants
                     $qb->update('App\Entity\VieDossier', 'vd')
                     ->set('vd.Date_traitement', ':dateTraitement')
                     ->set('vd.Statut_niveau_dossier', ':statutNiveauDossier')
                     ->set('vd.Id_acteur', ':idActeur')
                     ->set('vd.Date_enregistrement', 'vd.Date_enregistrement') 
                     ->where('vd.Reference_dossier = :referenceDossier')
                     ->andWhere('vd.Id_niveau = :idNiveau')
                     ->andWhere("vd.Statut_niveau_dossier = 'Nouveau' ")
                     ->setParameter('dateTraitement', new \DateTime())
                     ->setParameter('statutNiveauDossier', 'Terminé')
                     ->setParameter('referenceDossier', $Reference_dossier)
                     ->setParameter('idNiveau', $Id_niveau)
                     ->setParameter('idActeur', $Id_acteur)
                     ->getQuery()
                     ->execute();

                     // Création et Enregistrement d'une nouvelle instance de VieDossier
                     $nouvelleVieDossier = new VieDossier();
                     $nouvelleVieDossier->setReferenceDossier($Reference_dossier);
                     $nouvelleVieDossier->setIdNiveau($Id_niveau + 1);
                     $nouvelleVieDossier->setIdActeur($Id_acteur);
                     $nouvelleVieDossier->setDateEnregistrement($dateEnregistrement);
                     $nouvelleVieDossier->setDateEntree(new \DateTime());
                     $nouvelleVieDossier->setDateTraitement(new \DateTime());
                     $nouvelleVieDossier->setStatutNiveauDossier('Nouveau');
                     $entityManager->persist($nouvelleVieDossier);
                     $entityManager->flush();

                     // Création et Enregistrement d'une nouvelle instance de Transmission de bordereau
                     $transmission = new Bordereau();
                     $transmission->setIdExpediteur($Id_niveau);
                     $transmission->setIdDestinataire($Id_niveau + 1);
                     $transmission->setIdAgenceExpedition(1);
                     $transmission->setIdAgenceDestination(1);
                     $transmission->setReferenceDossier($Reference_dossier);
                     $transmission->setDateTransmission(new \DateTime());
                     $transmission->setStatutTransmission('A transmettre');
                     $entityManager->persist($transmission);
                     $entityManager->flush();

                     return new JsonResponse(['Message' => 'Validation réussie']);
                        }
                     }
                 } 
         }  
     }
     else{
         return new JsonResponse(['error' => 'Le dossier ne peut être validé de suite'], Response::HTTP_BAD_REQUEST) ;
     }
 }

}
