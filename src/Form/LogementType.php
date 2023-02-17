<?php

namespace App\Form;

use App\Entity\Logement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class LogementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('besoin', ChoiceType::class, [ 
                'label' => false,
                'placeholder' => '--',
                'choices'  => [
                       'Oui' => 1,
                       'Non' => 0,
                   ],
                ])
            ->add('descriptif', TextareaType::class, ['label' => false, 'attr' => ['placeholder' => 'Choisissez si vous avez besoin d\'un logement ou non.']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Logement::class,
        ]);
    }
}
