<?php

namespace App\Controller;

use App\Entity\Acteur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route; 
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ConnexionController extends AbstractController
{
    #[Route('/connexion', name: 'connexion', methods: ['POST'])]
public function connexion(Request $request, EntityManagerInterface $entityManager, Security $security, SessionInterface $session): JsonResponse
{
    $repository = $entityManager->getRepository(Acteur::class);

    $data = json_decode($request->getContent(), true);

    $Username = $data['Username'] ?? null;
    $Password = $data['Password'] ?? null;
    
    // Récupérer l'acteur actuellement connecté
    $acteur = $repository->findOneBy(['Username' => $Username]);

    if (!$acteur) {
        throw new BadCredentialsException('Informations incorrectes');
    }

    if (!$acteur->isValidPassword($Password)) {
        throw new BadCredentialsException('Mot de passe incorrect');
    }

    // Récupération des informations nécessaires à toute la session
    $Id_niveau = $acteur->getIdNiveau();
    $Id_agence = $acteur->getIdAgence();
    $Id_acteur = $acteur->getIdActeur();
    $Username = $acteur->getUsername(); 

    // Stockage dans la session
    $session->set('Id_niveau', $Id_niveau);
    $session->set('Id_agence', $Id_agence);
    $session->set('Id_acteur', $Id_acteur);
    $session->set('Username', $Username);

    return new JsonResponse(['Message' => 'Connexion réussie', 
                            'Id_niveau' => $Id_niveau, 
                            'Username' => $Username, 
                            'Id_agence' => $Id_agence, 
                            'Id_acteur' => $Id_acteur]);
}

}



