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
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\DataTransformer\StringToUploadedFileTransformer;


class MaterielModifierType extends AbstractType
{
    // Cette fonction permet de construire le formulaire
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

        // Ajout d'un transformer pour convertir une chaîne de caractères en objet File
        $builder->get('image')->addModelTransformer(new StringToUploadedFileTransformer());
    }

    // Cette fonction indique le formulaire parent dont ce formulaire hérite
    public function getParent()
    {
        return MaterielType::class;
    }

    // Cette fonction configure les options du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materiel::class, // Le formulaire est lié à l'entité Materiel
        ]);
    }
}
