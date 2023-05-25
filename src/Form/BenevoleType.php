<?php

namespace App\Form;

use App\Entity\Benevole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BenevoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('tel')
            ->add('pole', EntityType::class, [
                'class' => 'App\Entity\Pole',
                // L'intitulé du matériel sera utilisé pour l'afficher dans la liste déroulante
                'choice_label' => 'intitule',
                'multiple' => true,
                'expanded' => false,
                'choice_label' => 'intitule',
                'attr' => ['class' => 'pole']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Benevole::class,
        ]);
    }
}
