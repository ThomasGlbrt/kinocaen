<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Materiel;
use Doctrine\Persistence\ManagerRegistry;

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

}
