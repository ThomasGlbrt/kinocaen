<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Materiel;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\MaterielType;
use App\Form\MaterielModifierType;

class MaterielController extends AbstractController
{
    #[Route('/materiel', name: 'app_materiel')]
    public function index(): Response
    {
        return $this->render('materiel/index.html.twig', [
            'controller_name' => 'MaterielController',
        ]);
    }

    public function consulterMateriel(ManagerRegistry $doctrine, int $id){

		$materiel= $doctrine->getRepository(Materiel::class)->find($id);

		if (!$materiel) {
			throw $this->createNotFoundException(
            'Aucun materiel trouvé avec le numéro '.$id
			);
		}

		//return new Response('Materiel : '.$materiel->getNom());
		return $this->render('materiel/consulter.html.twig', [
            'materiel' => $materiel,]);
	}


    public function listerMateriel(ManagerRegistry $doctrine){

        $repository = $doctrine->getRepository(Materiel::class);

        $materiel= $repository->findAll();
        return $this->render('materiel/lister.html.twig', [
    'pMateriels' => $materiel,]);	
    
    }

    
    public function ajouterMateriel(ManagerRegistry $doctrine,Request $request){
        $materiel = new materiel();
	$form = $this->createForm(MaterielType::class, $materiel);
	$form->handleRequest($request);
 
	if ($form->isSubmitted() && $form->isValid()) {
 
            $materiel = $form->getData();
 
            $entityManager = $doctrine->getManager();
            $entityManager->persist($materiel);
            $entityManager->flush();
 
	    return $this->render('materiel/consulter.html.twig', ['materiel' => $materiel,]);
	}
	else
        {
            return $this->render('materiel/ajouter.html.twig', array('form' => $form->createView(),));
	    }
    }


    public function modifierMateriel(ManagerRegistry $doctrine, $id, Request $request){
 
        //récupération du matériel dont l'id est passé en paramètre
        $materiel = $doctrine
            ->getRepository(Materiel::class)
            ->find($id);
     
        if (!$materiel) {
            throw $this->createNotFoundException('Aucun Materiel trouvé avec le numéro '.$id);
        }
        else
        {
                $form = $this->createForm(MaterielModifierType::class, $materiel);
                $form->handleRequest($request);
     
                if ($form->isSubmitted() && $form->isValid()) {
     
                     $materiel = $form->getData();
                     $entityManager = $doctrine->getManager();
                     $entityManager->persist($materiel);
                     $entityManager->flush();
                     return $this->render('materiel/consulter.html.twig', ['materiel' => $materiel,]);
               }
               else{
                    return $this->render('materiel/ajouter.html.twig', array('form' => $form->createView(),));
               }
            }
     }


     public function supprimerMateriel(ManagerRegistry $doctrine, int $id, Request $request){

        $materiel= $doctrine->getRepository(Materiel::class)->find($id);
    
        if (!$materiel){
            throw $this->createNotFoundException('Aucun materiel trouvé avec cet id !');
        }
        else{
            $entityManager = $doctrine->getManager();
            $entityManager->remove($materiel);
            $entityManager->flush();
            return $this->redirectToRoute('materielLister');
        }
    }
}
