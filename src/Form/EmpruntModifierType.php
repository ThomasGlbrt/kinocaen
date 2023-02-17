<?php

namespace App\Form;

use App\Entity\Emprunt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

// Déclaration de la classe EmpruntModifierType qui hérite de AbstractType
class EmpruntModifierType extends AbstractType
{
    // Méthode pour construire le formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajout des champs au formulaire avec leurs options
        $builder
            // Champ pour sélectionner un objet Inscrit
            ->add('inscrit', EntityType::class, [
                'class' => 'App\Entity\Inscrit',
                // Utiliser le nom de l'inscrit comme étiquette de choix
                'choice_label' => 'nom',
            ])
            // Champ pour saisir la date de début de l'emprunt
            ->add('dateDebut', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            // Champ pour saisir la date de fin de l'emprunt
            ->add('dateFin', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            // Champ pour sélectionner un objet Materiel
            ->add('materiel', EntityType::class, [
                'class' => 'App\Entity\Materiel',
                // Utiliser l'intitulé du matériel comme étiquette de choix
                'choice_label' => 'intitule',
            ])
        ;
    }

    // Méthode pour définir les options par défaut du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Définir la classe de données du formulaire à Emprunt::class
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}
