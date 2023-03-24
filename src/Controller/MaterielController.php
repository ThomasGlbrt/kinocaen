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
use App\Entity\Categorie;
use App\Service\FileUploader;
use Symfony\Component\Form\Extension\Core\DataTransformer\StringToUploadedFileTransformer;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\File\UploadedFile;


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


    public function listerMateriel(ManagerRegistry $doctrine)
{
    $repository = $doctrine->getRepository(Materiel::class);
    $materiels = $repository->findAll();
    $categorieMateriels = [];

    foreach ($materiels as $materiel) {
        $categorie = $materiel->getCategorie()->getLibelle();
        if (!array_key_exists($categorie, $categorieMateriels)) {
            $categorieMateriels[$categorie] = [];
        }
        $categorieMateriels[$categorie][] = $materiel;
    }

    return $this->render('materiel/lister.html.twig', [
        'categorieMateriels' => $categorieMateriels,
    ]);
}


public function ajouterMateriel(Request $request, FileUploader $fileUploader, ManagerRegistry $doctrine)
{
    $materiel = new Materiel();
    $form = $this->createForm(MaterielType::class, $materiel);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('image')->getData();
if ($imageFile) {
    $intitule = $materiel->getIntitule();
    $imageFileName = $fileUploader->FileUploader($imageFile, $intitule . '.' . $imageFile->guessExtension());
    $materiel->setImage($imageFileName);
}

        $entityManager = $doctrine->getManager();
        $entityManager->persist($materiel);
        $entityManager->flush();

        $materielId = $materiel->getId();

        return $this->redirectToRoute('materielConsulter', ['id' => $materiel->getId()]);

        if ($file) {
            $fileName = $fileUploader->FileUploader($file, $intitle.'.'.$file->guessExtension());
            $materiel->setImage($imageActuelle);
        }
    }

    return $this->render('materiel/ajouter.html.twig', [
        'form' => $form->createView(),
    ]);
}


public function modifierMateriel(ManagerRegistry $doctrine, int $id, Request $request, FileUploader $fileUploader): Response
    {
        // Récupération du matériel à modifier depuis la base de données
        $entityManager = $doctrine->getManager();
        $materiel = $entityManager->getRepository(Materiel::class)->find($id);

        if (!$materiel) {
            throw $this->createNotFoundException('Aucun matériel trouvé pour l\'id '.$id);
        }

        // Stockage de l'image actuelle du matériel
        $imageActuelle = $materiel->getImage();

        // Création d'un formulaire pour la modification du matériel
        $form = $this->createForm(MaterielModifierType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                // Suppression de l'ancienne image du matériel si elle existe
                if ($imageActuelle && file_exists($this->getParameter('images_directory').'/'.$imageActuelle)) {
                    unlink($this->getParameter('images_directory').'/'.$imageActuelle);
                }

                // Enregistrement de la nouvelle image du matériel
                $fileName = $fileUploader->upload($imageFile);
                $materiel->setImage($fileName);
            } else {
                $materiel->setImage($imageActuelle);
            }

            // Enregistrement des modifications du matériel dans la base de données
            $entityManager->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->render('materiel/ajouter.html.twig', [
            'form' => $form->createView(),
            'materiel' => $materiel,
        ]);
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
