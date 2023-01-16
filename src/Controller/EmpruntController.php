<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Emprunt;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\EmpruntType;
use App\Form\EmpruntModifierType;

class EmpruntController extends AbstractController
{
    #[Route('/emprunt', name: 'app_emprunt')]
    public function index(): Response
    {
        return $this->render('emprunt/index.html.twig', [
            'controller_name' => 'EmpruntController',
        ]);
    }

    public function consulterEmprunt(ManagerRegistry $doctrine, int $id){

		$emprunt= $doctrine->getRepository(Emprunt::class)->find($id);

		if (!$emprunt) {
			throw $this->createNotFoundException(
            'Aucun emprunt trouvé avec le numéro '.$id
			);
		}

		//return new Response('Emprunt : '.$emprunt->getNom());
		return $this->render('emprunt/consulter.html.twig', [
            'emprunt' => $emprunt,]);
	}


    public function listerEmprunt(ManagerRegistry $doctrine){

        $repository = $doctrine->getRepository(Emprunt::class);

        $emprunt= $repository->findAll();
        return $this->render('emprunt/lister.html.twig', [
    'pEmprunts' => $emprunt,]);	
    
    }

    
    public function ajouterEmprunt(ManagerRegistry $doctrine,Request $request){
        $emprunt = new emprunt();
	$form = $this->createForm(EmpruntType::class, $emprunt);
	$form->handleRequest($request);
 
	if ($form->isSubmitted() && $form->isValid()) {
 
            $emprunt = $form->getData();
 
            $entityManager = $doctrine->getManager();
            $entityManager->persist($emprunt);
            $entityManager->flush();
 
	    return $this->render('emprunt/consulter.html.twig', ['emprunt' => $emprunt,]);
	}
	else
        {
            return $this->render('emprunt/ajouter.html.twig', array('form' => $form->createView(),));
	    }
    }


    public function modifierEmprunt(ManagerRegistry $doctrine, $id, Request $request){
 
        //récupération du matériel dont l'id est passé en paramètre
        $emprunt = $doctrine
            ->getRepository(Emprunt::class)
            ->find($id);
     
        if (!$emprunt) {
            throw $this->createNotFoundException('Aucun Emprunt trouvé avec le numéro '.$id);
        }
        else
        {
                $form = $this->createForm(EmpruntModifierType::class, $emprunt);
                $form->handleRequest($request);
     
                if ($form->isSubmitted() && $form->isValid()) {
     
                     $emprunt = $form->getData();
                     $entityManager = $doctrine->getManager();
                     $entityManager->persist($emprunt);
                     $entityManager->flush();
                     return $this->render('emprunt/consulter.html.twig', ['emprunt' => $emprunt,]);
               }
               else{
                    return $this->render('emprunt/ajouter.html.twig', array('form' => $form->createView(),));
               }
            }
     }


     public function supprimerEmprunt(ManagerRegistry $doctrine, int $id, Request $request){

        $emprunt= $doctrine->getRepository(Emprunt::class)->find($id);
    
        if (!$emprunt){
            throw $this->createNotFoundException('Aucun emprunt trouvé avec cet id !');
        }
        else{
            $entityManager = $doctrine->getManager();
            $entityManager->remove($emprunt);
            $entityManager->flush();
            return $this->redirectToRoute('empruntLister');
        }
    }
}
