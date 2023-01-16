<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Inscrit;
use App\Entity\UtilisateurType;
use App\Form\InscritModifierType;
use Doctrine\Persistence\ManagerRegistry;

class InscritController extends AbstractController
{
    #[Route('/inscrit', name: 'app_inscrit')]
    public function index(): Response
    {
        return $this->render('registration/register.html.twig', [
            'controller_name' => 'InscritController',
        ]);
    }

    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Inscrit();
        $form = $this->createForm(InscritType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('accueil');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    public function consulterInscrit(ManagerRegistry $doctrine, int $id){

		$inscrit= $doctrine->getRepository(Inscrit::class)->find($id);

		if (!$inscrit) {
			throw $this->createNotFoundException(
            'Aucun inscrit trouvé avec le numéro '.$id
			);
		}

		//return new Response('Inscrit : '.$inscrit->getNom());
		return $this->render('inscrit/consulter.html.twig', [
            'inscrit' => $inscrit,]);
	}

    public function modifierInscrit(ManagerRegistry $doctrine,int $id, Request $request){
 
        //récupération du matériel dont l'id est passé en paramètre
        $inscrit = $doctrine
            ->getRepository(Inscrit::class)
            ->find($id);
     
        if (!$inscrit) {
            throw $this->createNotFoundException('Aucun Inscrit trouvé avec le numéro '.$id);
        }
        else
        {
                $form = $this->createForm(InscritModifierType::class, $inscrit);
                $form->handleRequest($request);
     
                if ($form->isSubmitted() && $form->isValid()) {
     
                     $inscrit = $form->getData();
                     $entityManager = $doctrine->getManager();
                     $entityManager->persist($inscrit);
                     $entityManager->flush();
                     return $this->render('inscrit/consulter.html.twig', ['inscrit' => $inscrit,]);

                     return $this->redirectToRoute('accueil');
               }
               else{
                    return $this->render('inscrit/modifier.html.twig', array('form' => $form->createView(),));
               }
            }
     }

}
