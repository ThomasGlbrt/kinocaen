<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Inscrit;
use App\Entity\TypePaiement;
use App\Entity\Metier;
use App\Entity\Logement;
use App\Entity\Utilisateur;
use App\Entity\TypeCompetences;
use App\Entity\UtilisateurType;
use App\Form\InscritModifierType;
use App\Form\InscritType;
use App\Service\FileUploader;
use Knp\Snappy\Pdf;
use Dompdf\Dompdf;
use Imagick;
use Doctrine\Persistence\ManagerRegistry;
use App\Twig\FormatTelephoneExtension;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\ORM\EntityManagerInterface;

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

    public function register(
        ManagerRegistry $doctrine,
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new Inscrit();
        $logement = new Logement();
        $utilisateurs = new Utilisateur();
        $competences = $doctrine->getRepository(TypeCompetences::class)->findAll();
        $form = $this->createForm(InscritType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $utilisateurs->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
    
            // Add user to sessions
            $sessions = $form->get('Session')->getData();
            
            $sessionPorteurProjet = $form->get('Session2')->getData();
            $sessionPorteurProjet2 = $form->get('Session3')->getData();
            print_r($sessions.$sessionPorteurProjet.$sessionPorteurProjet2);
            $user->addSessions($sessions);
    
            $file = $form->get('image')->getData();
            $nom = $form->get('nom')->getData();
            $prenom = $form->get('prenom')->getData();
            if ($file) {
                if ($file->guessExtension() == 'png' || $file->guessExtension() == 'jpg') {
                    $fileName = $fileUploader->FileUploader($file, $nom.$prenom.'.'.$file->guessExtension());
                } else {
                    $fileName = $fileUploader->FileUploader($file, $nom.$prenom.'.'.'png');
                }
                $image = new Imagick($filename);
                $image->resizeImage(1080, 1350, Imagick::FILTER_LANCZOS, 1);
                $inscrit->setImage($image);
            } else {
                $inscrit->setImage($imageActuelle);
            }
    
            $essai = $form->get('Essai')->getData();
            $inscrit->setTypeCompetence()->setEssai($essai);
    
            $logement->setLogement($form->get('logement')->getData());
            $entityManager->persist($logement);
            $entityManager->persist($user);
            $entityManager->flush();
    
            return $this->redirectToRoute('last_page');
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

    public function modifierInscrit(ManagerRegistry $doctrine,int $id, Request $request, FileUploader $fileUploader)
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
                $fileName = $fileUploader->FileUploader($file,$nom.$prenom.'.'.$file->guessExtension());
                $inscrit->setImage($fileName);
            }else{
                $inscrit->setImage($imageActuelle);
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

    
    
    


public function trombiInscrit(ManagerRegistry $doctrine){

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
    $dompdf->set_option('defaultFont', 'Arial');
    $dompdf->set_option('enable_javascript', true);
    $dompdf->set_option('enable_smart_shrinking', true);
    $dompdf->set_paper('A4', 'portrait', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
    $dompdf->render();

    $nom = $inscrit->getNom();
    $prenom = $inscrit->getPrenom();

    $response = new Response($dompdf->output());
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="'.$nom.'-'.$prenom.'.pdf"');

    return $response;
}

public function telechargerTrombiPdf(ManagerRegistry $doctrine)
{
    $inscrits = $doctrine->getRepository(Inscrit::class)->findAll();

    if (!$inscrits) {
        throw $this->createNotFoundException(
            'Aucun inscrits'
        );
    }

    $html = $this->renderView('inscrit/pdfTrombi.html.twig', [
        'inscrits' => $inscrits,
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
    $dompdf->set_option('defaultFont', 'Arial');
    $dompdf->set_option('enable_css_float', true);
    $dompdf->set_option('enable_html5_parser', true);
    $dompdf->set_option('enable_javascript', true);
    $dompdf->set_option('enable_smart_shrinking', true);
    $dompdf->set_paper('A4', 'portrait', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
    $dompdf->render();


    $response = new Response($dompdf->output());
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="trombinoscope.pdf"');

    return $response;
}

public function listerInscrit(ManagerRegistry $doctrine){

    $repository = $doctrine->getRepository(Inscrit::class);

    $inscrits= $repository->findAll();
    return $this->render('inscrit/lister.html.twig', [
'inscrits' => $inscrits,]);	
}

public function supprimerInscrit(ManagerRegistry $doctrine, int $id, Request $request)
{
    $inscrit = $doctrine->getRepository(Inscrit::class)->find($id);

    if (!$inscrit) {
        throw $this->createNotFoundException('Aucun inscrit trouvé avec cet id !');
    }

    $emprunts = $inscrit->getEmprunts();

    if (count($emprunts) > 0) {
        $this->addFlash('error', 'Cet utilisateur a des emprunts en cours.');
        return $this->redirectToRoute('consulterEmpruntsInscrit', ['id' => $inscrit->getId()]);
    }

    $entityManager = $doctrine->getManager();
    $entityManager->remove($inscrit);
    $entityManager->flush();

    return $this->redirectToRoute('listerInscrit');
}



}
