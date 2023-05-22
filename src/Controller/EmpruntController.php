<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Emprunt;
use App\Entity\Inscrit;
use App\Entity\Materiel;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\EmpruntType;
use App\Form\EmpruntModifierType;
use App\Form\EmpruntAdminType;

class EmpruntController extends AbstractController
{
    /**
     * Route principale pour la page d'emprunt.
     *
     * @Route("/emprunt", name="app_emprunt")
     * 
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('emprunt/index.html.twig', [
            'controller_name' => 'EmpruntController',
        ]);
    }


    /**
     * Afficher les détails d'un emprunt en particulier.
     *
     * @param ManagerRegistry $doctrine
     * @param int $id
     * 
     * @return Response
     */
    public function consulterEmprunt(ManagerRegistry $doctrine, int $id)
    {
        // Récupération de l'emprunt correspondant à l'ID passé en paramètre
        $emprunt = $doctrine->getRepository(Emprunt::class)->find($id);

        // Si aucun emprunt n'est trouvé, lancer une exception
        if (!$emprunt) {
            throw $this->createNotFoundException(
                'Aucun emprunt trouvé avec le numéro '.$id
            );
        }

        // Retourner la vue associée à la consultation d'un emprunt
        return $this->render('emprunt/consulter.html.twig', [
            'emprunt' => $emprunt,
        ]);
    }


    /**
     * Afficher la liste de tous les emprunts.
     *
     * @param ManagerRegistry $doctrine
     * 
     * @return Response
     */
    public function listerEmprunt(ManagerRegistry $doctrine)
    {
        // Récupération du repository pour la classe Emprunt
        $repository = $doctrine->getRepository(Emprunt::class);

        // Récupération de tous les objets Emprunt de la BDD
        $emprunt = $repository->findAll();

        // Récupération de tous les objets Materiel de la BDD
        $materiel = $doctrine->getRepository(Materiel::class)->findAll();

        // Renvoi de la vue avec les informations des emprunts et des matériels
        return $this->render('emprunt/lister.html.twig', [
            'pEmprunts' => $emprunt, 'pMateriels' => $materiel,
        ]);
    }


    public function ajouterEmprunt(ManagerRegistry $doctrine, Request $request, $id)
{
    // Instanciation d'un nouvel objet Emprunt
    $emprunt = new Emprunt();
    // Récupération de l'utilisateur connecté
    $inscrit = $this->getUser()->getInscrit();
    // Récupération du matériel à partir de son ID
    $materiel = $doctrine->getRepository(Materiel::class)->find($id);
    // Association du matériel à l'emprunt
    $emprunt->setMateriel($materiel);
    // Association de l'utilisateur à l'emprunt
    $emprunt->setInscrit($inscrit);
    // Création du formulaire d'emprunt
    $form = $this->createForm(EmpruntType::class, $emprunt);
    // Traitement des données du formulaire
    $form->handleRequest($request);
    // Récupération des emprunts associés au matériel
    $emprunts = $doctrine->getRepository(Emprunt::class)->findBy(['materiel' => $materiel]);
    // Détermination de l'existence d'emprunts associés au matériel
    $empruntExists = count($emprunts) > 0;

    // Vérification si le matériel est déjà emprunté
    if ($empruntExists) {
        return $this->render('emprunt/ajouter.html.twig', [
            'form' => $form->createView(),
            'erreur' => 'Ce matériel est déjà emprunté.',
            'pEmprunts' => $emprunts,
            'empruntExists' => $empruntExists
        ]);
    }

    // Si le formulaire a été soumis et est valide
    if ($form->isSubmitted() && $form->isValid()) {
        // Persiste l'emprunt
        $entityManager = $doctrine->getManager();
        $entityManager->persist($emprunt);
        $entityManager->flush();
        return $this->redirectToRoute('empruntLister');
    }

    return $this->render('emprunt/ajouter.html.twig', [
        // Le formulaire est renvoyé avec la liste des emprunts.
        'form' => $form->createView(),
        'pEmprunts' => $emprunts,
        'empruntExists' => $empruntExists
    ]);
}


public function modifierEmprunt(ManagerRegistry $doctrine, int $id, Request $request): Response
{
    // Récupération de l'emprunt à modifier depuis la base de données
    $entityManager = $doctrine->getManager();
    $emprunt = $entityManager->getRepository(Emprunt::class)->find($id);

    if (!$emprunt) {
        throw $this->createNotFoundException('Aucun emprunt trouvé pour l\'id '.$id);
    }

    // Création d'un formulaire pour la modification de l'emprunt
    $form = $this->createForm(EmpruntModifierType::class, $emprunt);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrement des modifications de l'emprunt dans la base de données
        $entityManager->flush();

        return $this->redirectToRoute('empruntConsulter', ['id' => $emprunt->getId()]);
    }

    return $this->render('emprunt/modifier.html.twig', [
        'form' => $form->createView(),
        'emprunt' => $emprunt,
    ]);
}


    public function supprimerEmprunt(ManagerRegistry $doctrine, int $id, Request $request)
    {
        // Récupération de l'emprunt correspondant à l'id donné en paramètre
        $emprunt = $doctrine->getRepository(Emprunt::class)->find($id);

        // Si aucun emprunt n'est trouvé pour cet id
        if (!$emprunt){
            throw $this->createNotFoundException('Aucun emprunt trouvé avec cet id !');
        }
        else{
            // Suppression de l'emprunt de la base de données
            $entityManager = $doctrine->getManager();
            $entityManager->remove($emprunt);
            $entityManager->flush();
        
            // Redirection vers la liste des emprunts
            return $this->redirectToRoute('empruntLister');
        }
    }


    public function adminEmprunt(ManagerRegistry $doctrine, Request $request)
    {
        // Création d'un nouvel objet Emprunt
        $emprunt = new Emprunt();
        
        // Création du formulaire lié à l'objet Emprunt
        $form = $this->createForm(EmpruntAdminType::class, $emprunt);
        
        // Traitement des données du formulaire
        $form->handleRequest($request);
    
        // Vérification si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données soumises par le formulaire
            $emprunt = $form->getData();
    
            // Récupération du gestionnaire d'entités
            $entityManager = $doctrine->getManager();
    
            // Persistence de l'objet Emprunt
            $entityManager->persist($emprunt);
    
            // Enregistrement en base de données
            $entityManager->flush();
    
            // Redirection vers la vue de consultation de l'emprunt
            return $this->render('emprunt/consulter.html.twig', [
                'emprunt' => $emprunt,
            ]);
        } else {
            // Si le formulaire n'a pas été soumis ou les données sont invalides,
            // redirection vers la vue de création de l'emprunt
            return $this->render('emprunt/ajouterAdmin.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }
    
    
    public function consulterEmpruntsPerso(ManagerRegistry $doctrine, $id)
{
    // Récupération de l'utilisateur dont on veut consulter les emprunts
    $user = $doctrine->getRepository(Inscrit::class)->find($id);

    // Récupération de tous les emprunts associés à l'utilisateur
    $emprunts = $doctrine->getRepository(Emprunt::class)->findBy(['inscrit' => $user]);

    // Retourner la vue associée à la consultation des emprunts de l'utilisateur
    return $this->render('emprunt/consulterPerso.html.twig', [
        'pEmprunts' => $emprunts,
    ]);
}


}