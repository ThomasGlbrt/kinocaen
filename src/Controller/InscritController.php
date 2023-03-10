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

    public function register(ManagerRegistry $doctrine,Request $request,UserPasswordHasherInterface $userPasswordHasher,EntityManagerInterface $entityManager,FileUploader $fileUploader): Response {
        // Créer de nouveaux objets pour les différentes entités
        $user = new Inscrit();
        $logement = new Logement();
        $utilisateurs = new Utilisateur();
    
        // Récupérer toutes les compétences de la base de données
        $competences = $doctrine->getRepository(TypeCompetences::class)->findAll();
    
        // Créer un formulaire pour l'inscription d'un utilisateur
        $form = $this->createForm(InscritType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // encoder le mot de passe en clair
            $utilisateurs->setPassword(
                $userPasswordHasher->hashPassword(
                    $utilisateurs,
                    $form->get('utilisateurs')->get('plainPassword')->getData()
                )
            );
    
            // Ajouter l'utilisateur aux sessions
            $session = $form->get('Session')->getData();
            $sessionPorteurProjet = $form->get('Session2')->getData();
            $sessionPorteurProjet2 = $form->get('Session3')->getData();
            echo($session);
            $sessionCollection = $form->get('Session')->getData();
            /*foreach ($sessionCollection as $session) {
                if ($session instanceof Session) {
                    $user->addSession($session);
                }
            }*/
    
            // Ajouter une image de profil pour l'utilisateur
            $file = $form->get('image')->getData();
            $nom = $form->get('nom')->getData();
            $prenom = $form->get('prenom')->getData();
            if ($file) {
                if ($file->guessExtension() == 'png' || $file->guessExtension() == 'jpg') {
                    $fileName = $fileUploader->FileUploader($file, $nom.$prenom.'.'.$file->guessExtension());
                } else {
                    $fileName = $fileUploader->FileUploader($file, $nom.$prenom.'.'.'png');
                }
                $user->setImage($file);
            } else {
                $user->setImage($imageActuelle);
            }
    
            // Ajouter les compétences sélectionnées pour l'utilisateur
            $competencesCollection = $form->get('Competences')->getData();
            /*foreach ($competencesCollection as $competence) {
                if ($competence instanceof Session) {
                    $user->addCompetence($competence);
                }
            }*/
    
            // Ajouter les essais sélectionnées pour l'utilisateur
            $essaiCollection = $form->get('Essai')->getData();
            /*foreach ($essaiCollection as $essai) {
                if ($essai instanceof Session) {
                    $user->addEssai($essai);
                }
            }*/
    
            // Ajouter l'id de logement sélectionné pour l'utilisateur
            $formLogement = $form->get('LogementId')->getData();
            $user->setLogementId($formLogement);
            $entityManager->persist($user);
            $entityManager->flush();
    
            // Rediriger l'utilisateur vers la dernière page
            return $this->redirectToRoute('last_page');
        }
    
        // Afficher la vue pour le formulaire d'inscription
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    

    public function consulterInscrit(ManagerRegistry $doctrine, int $id){
        // Récupération de l'objet Inscrit avec l'id passé en paramètre
        $inscrit= $doctrine->getRepository(Inscrit::class)->find($id);
    
        // Si l'objet n'existe pas, renvoyer une exception avec un message d'erreur
        if (!$inscrit) {
            throw $this->createNotFoundException(
            'Aucun inscrit trouvé avec le numéro '.$id
            );
        }
    
        // Rendu de la vue 'inscrit/consulter.html.twig' avec l'objet Inscrit passé en paramètre
        return $this->render('inscrit/consulter.html.twig', [
            'inscrit' => $inscrit,
        ]);
    }
    
    public function modifierInscrit(ManagerRegistry $doctrine,int $id, Request $request, FileUploader $fileUploader)
    {
        // Récupération de l'objet Inscrit avec l'id passé en paramètre
        $inscrit = $doctrine->getRepository(Inscrit::class)->find($id);
        // Récupération de tous les objets Metier de la base de données
        $metiers = $doctrine->getRepository(Metier::class)->findAll();
    
        // Si l'objet Inscrit n'existe pas, renvoyer une exception avec un message d'erreur
        if (!$inscrit) {
            throw $this->createNotFoundException('Aucun Inscrit trouvé avec le numéro '.$id);
        }
        else
        {
            // Stockage de l'image actuelle de l'objet Inscrit
            $imageActuelle = $inscrit->getImage();
    
            // Création du formulaire de modification à partir de la classe InscritModifierType
            $form = $this->createForm(InscritModifierType::class, $inscrit);
            // Passage des objets Metier au formulaire
            $form->get('metier')->setData($metiers);
            $form->handleRequest($request);
    
            // Si le formulaire est soumis et valide
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UploadedFile $file */
                $file = $form->get('image')->getData();
                $nom = $form->get('nom')->getData();
                $prenom = $form->get('prenom')->getData();
    
                // Si un fichier est téléchargé
                if ($file) {
                    // Enregistrement du fichier et récupération du nom de fichier
                    $fileName = $fileUploader->FileUploader($file,$nom.$prenom.'.'.$file->guessExtension());
                    // Stockage du nom de fichier dans l'objet Inscrit
                    $inscrit->setImage($fileName);
                }else{
                    // Sinon, restauration de l'image précédente
                    $inscrit->setImage($imageActuelle);
                }
    
                // Récupération de l'EntityManager
                $entityManager = $doctrine->getManager();
                // Persistance de l'objet Inscrit modifié
                $entityManager->persist($inscrit);
                // Sauvegarde des modifications en base de données
                $entityManager->flush();
                // Redirection vers la page d'accueil
                return $this->redirectToRoute('accueil');
            } else {
                // Rendu de la vue 'inscrit/modifier.html.twig' avec le formulaire de modification
                return $this->render('inscrit/modifier.html.twig', array('form' => $form->createView(),));
            }
    
    }
}

    
    
    


// Cette fonction récupère tous les inscrits enregistrés en base de données et les affiche sur la page 'trombi.html.twig'
public function trombiInscrit(ManagerRegistry $doctrine){

    // Récupération du repository pour l'entité 'Inscrit'
    $repository = $doctrine->getRepository(Inscrit::class);

    // Récupération de tous les inscrits enregistrés en base de données
    $inscrits= $repository->findAll();

    // Renvoi des inscrits à la vue 'trombi.html.twig'
    return $this->render('inscrit/trombi.html.twig', [
        'inscrits' => $inscrits,
    ]);	
}

// Cette fonction génère un PDF contenant les informations d'un inscrit donné en paramètre
public function telechargerInscritPdf(ManagerRegistry $doctrine, int $id)
{
    // Récupération de l'inscrit correspondant à l'identifiant $id
    $inscrit = $doctrine->getRepository(Inscrit::class)->find($id);

    // Si aucun inscrit n'a été trouvé avec l'identifiant $id, une exception est levée
    if (!$inscrit) {
        throw $this->createNotFoundException(
            'Aucun inscrit trouvé avec le numéro '.$id
        );
    }

    // Génération de la vue 'pdf.html.twig' en utilisant les informations de l'inscrit récupéré
    $html = $this->renderView('inscrit/pdf.html.twig', [
        'inscrit' => $inscrit,
    ]);

    // Configuration de DOMPDF en fonction des besoins
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

    // Récupération du nom et du prénom de l'inscrit pour nommer le fichier PDF généré
    $nom = $inscrit->getNom();
    $prenom = $inscrit->getPrenom();

    // Création d'une réponse HTTP contenant le PDF généré, avec le bon type MIME et le nom de fichier approprié
    $response = new Response($dompdf->output());
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="'.$nom.'-'.$prenom.'.pdf"');

    // Renvoi de la réponse HTTP
    return $response;
}

public function telechargerTrombiPdf(ManagerRegistry $doctrine)
{
    // Récupération de tous les inscrits
    $inscrits = $doctrine->getRepository(Inscrit::class)->findAll();

    // Si aucun inscrit trouvé, une exception est lancée
    if (!$inscrits) {
        throw $this->createNotFoundException(
            'Aucun inscrits'
        );
    }

    // Génération du HTML à partir d'un template Twig
    $html = $this->renderView('inscrit/pdfTrombi.html.twig', [
        'inscrits' => $inscrits,
    ]);

    // Configuration de DOMPDF selon vos besoins
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

    // Création de la réponse HTTP avec le contenu PDF généré par DOMPDF
    $response = new Response($dompdf->output());
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="trombinoscope.pdf"');

    return $response;
}

public function listerInscrit(ManagerRegistry $doctrine){

    // Récupération de tous les inscrits
    $repository = $doctrine->getRepository(Inscrit::class);
    $inscrits= $repository->findAll();

    // Renvoie d'une réponse HTTP avec le template Twig "lister.html.twig" et la liste des inscrits récupérés
    return $this->render('inscrit/lister.html.twig', [
        'inscrits' => $inscrits,
    ]);
}

}