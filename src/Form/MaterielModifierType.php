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

class MaterielModifierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Récupération du matériel à modifier depuis l'option "data"
        $materiel = $options['data'];

        // Ajout d'un champ "intitule" de type "text" avec la valeur du matériel
        $builder->add('intitule', TextType::class)

        // Ajout d'un champ "description" de type "textarea" avec la valeur du matériel
        ->add('description', TextareaType::class, [
            'required' => false,
        ])

        // Ajout d'un champ "categorie" de type "entity", lié à l'entité "Categorie"
        // Le champ affiche la propriété "libelle" des entités "Categorie" avec la valeur du matériel
        ->add('categorie', EntityType::class, [
            'class' => 'App\Entity\Categorie',
            'choice_label' => 'libelle',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materiel::class,
        ]);
    }
}
