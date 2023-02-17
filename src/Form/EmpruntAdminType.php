<?php

namespace App\Form;

use App\Entity\Emprunt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EmpruntAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajout des champs au formulaire avec leur type et leurs options
        $builder
            ->add('inscrit', EntityType::class, [
                'class' => 'App\Entity\Inscrit',
                // Le nom de l'inscrit sera utilisé pour l'afficher dans la liste déroulante
                'choice_label' => 'nom',
            ])
            ->add('dateDebut', DateTimeType::class, [
                // Affichage du champ dateDebut sous forme de champ de saisie simple
                'widget' => 'single_text',
            ])
            ->add('dateFin', DateTimeType::class, [
                // Affichage du champ dateFin sous forme de champ de saisie simple
                'widget' => 'single_text',
            ])
            ->add('materiel', EntityType::class, [
                'class' => 'App\Entity\Materiel',
                // L'intitulé du matériel sera utilisé pour l'afficher dans la liste déroulante
                'choice_label' => 'intitule',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Définition de la classe associée aux données du formulaire
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}