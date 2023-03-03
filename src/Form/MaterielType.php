<?php

namespace App\Form;

use App\Entity\Materiel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajout d'un champ "intitule" de type "text"
        $builder->add('intitule')

        // Ajout d'un champ "description" de type "textarea"
        ->add('description')

        // Ajout d'un champ "categorie" de type "entity", lié à l'entité "Categorie"
        // Le champ affiche la propriété "libelle" des entités "Categorie"
        ->add('categorie', EntityType::class, [
            'class' => 'App\Entity\Categorie',
            'choice_label' => 'libelle',
        ])

        // Ajout d'un champ "image" de type "file"
        // Le champ n'est pas obligatoire
        ->add('image', FileType::class, [
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Définition de l'entité associée au formulaire
        $resolver->setDefaults([
            'data_class' => Materiel::class,
        ]);
    }
}
