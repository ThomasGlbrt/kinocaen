<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Inscrit;
use App\Entity\Metier;
use App\Entity\UtilisateurType;
use App\Form\InscritModifierType;
use Knp\Snappy\Pdf;
use Dompdf\Dompdf;
use Doctrine\Persistence\ManagerRegistry;

class InscritController extends AbstractController
{
    protected $container;

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

    public function modifierInscrit(ManagerRegistry $doctrine,int $id, Request $request)
    {
        // récupération de l'inscrit dont l'id est passé en paramètre
        $inscrit = $doctrine->getRepository(Inscrit::class)->find($id);
        $metiers = $doctrine->getRepository(Metier::class)->findAll();

        if (!$inscrit) {
            throw $this->createNotFoundException('Aucun Inscrit trouvé avec le numéro '.$id);
        }
        else
        {
            // stocker l'image actuelle
            $imageActuelle = $inscrit->getImage();

            $form = $this->createForm(InscritModifierType::class, $inscrit);
            $form->get('metier')->setData($metiers);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UploadedFile $file */
                $file = $form->get('image')->getData();
                $nom = $form->get('nom')->getData();
                $prenom = $form->get('prenom')->getData();
                if ($file) {
                    // vérifie si un fichier avec le même nom existe déjà
                    $i = 1;
                    $originalFilename = $nom.$prenom.'.'.$file->guessExtension();
                    $filename = $originalFilename;
                    while (file_exists($this->getParameter('images_directory') . '/' . $filename)) {
                        $filename = $nom.$prenom.$i.'.'.$file->guessExtension();
                        $i++;
                    }
                    try {
                        $file->move(
                            $this->getParameter('images_directory'),
                            $filename
                        );
                        $inscrit->setImage($filename);
                    } catch (FileException $e) {
                        // ... gérer l'exception si l'upload a échoué
                    }
                }else{
                    $inscrit->setImage($inscrit->getImage());
                }
                $entityManager = $doctrine->getManager();
                $entityManager->persist($inscrit);
                $entityManager->flush();
            return $this->redirectToRoute('accueil');
            } else {
                return $this->render('inscrit/modifier.html.twig', array('form' => $form->createView(),));
                }
    }
    }
    


public function listerInscrit(ManagerRegistry $doctrine){

    $repository = $doctrine->getRepository(Inscrit::class);

    $inscrits= $repository->findAll();
    return $this->render('inscrit/trombi.html.twig', [
'inscrits' => $inscrits,]);	

}
public function telechargerInscritPdf(ManagerRegistry $doctrine, int $id)
{
    $inscrit = $doctrine->getRepository(Inscrit::class)->find($id);

    if (!$inscrit) {
        throw $this->createNotFoundException(
            'Aucun inscrit trouvé avec le numéro '.$id
        );
    }

    $html = $this->renderView('inscrit/pdf.html.twig', [
        'inscrit' => $inscrit,
    ]);

    // Configure DOMPDF according to your needs
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->set_option('defaultMediaType', 'all');
    $dompdf->set_option('isHtml5ParserEnabled', true);
    $dompdf->set_option('isRemoteEnabled', true);
    $dompdf->set_option('enable_remote', true);
    $dompdf->set_option('enable_css_float', true);
    $dompdf->set_option('enable_html5_parser', true);
    $dompdf->set_option('enable_javascript', true);
    $dompdf->set_option('enable_smart_shrinking', true);
    $dompdf->set_option('enable_remote', true);
    $dompdf->set_option('defaultFont', 'Arial');
    $dompdf->set_option('enable_remote', true);
    $dompdf->set_option('enable_css_float', true);
    $dompdf->set_option('enable_html5_parser', true);
    $dompdf->set_option('enable_javascript', true);
    $dompdf->set_option('enable_smart_shrinking', true);
    $dompdf->set_option('defaultFont', 'Arial');
    $dompdf->set_paper('A4', 'portrait', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
    $dompdf->render();

    $nom = $inscrit->getNom();
    $prenom = $inscrit->getPrenom();

    $response = new Response($dompdf->output());
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="'.$nom.'-'.$prenom.'.pdf"');

    return $response;
}

}
