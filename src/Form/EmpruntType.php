<?php

namespace App\Form;

use App\Entity\Emprunt;
use App\Entity\Inscrit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class EmpruntType extends AbstractType
{
    /**
     * Fonction permettant de construire le formulaire
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Ajout d'un champ inscrit en utilisant le type InscritType
            ->add('inscrit', InscritType::class, [
                'required' => false,
                'attr' => [
                    // Attribut permettant de cacher le champ (un utilisateur réserve seulement pour lui)
                    'style' => 'display:none;',
                ],
            ])
            // Ajout d'un champ dateDebut en utilisant le type DateTimeType
            ->add('dateDebut', DateTimeType::class, [
                // Permet de n'afficher que la date, sans l'heure
                'widget' => 'single_text',
            ])
            // Ajout d'un champ dateFin en utilisant le type DateTimeType
            ->add('dateFin', DateTimeType::class, [
                // Permet de n'afficher que la date, sans l'heure
                'widget' => 'single_text',
            ])
            // Ajout d'un champ materiel en utilisant le type EntityType
            ->add('materiel', EntityType::class, [
                // Définit la classe associée au champ
                'class' => 'App\Entity\Materiel',
                // Définit la valeur affichée dans les options
                'choice_label' => 'intitule',
                'attr' => [
                    // Attribut permettant de cacher le champ
                    'style' => 'display:none;',
                ],
            ])
        ;
    }

    /**
     * Fonction permettant de configurer les options du formulaire
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Définit la classe associée au formulaire
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}
