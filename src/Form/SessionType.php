<?php

namespace App\Form;

use App\Entity\Session;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('intitule', ChoiceType::class, [
            'label' => 'A quel session ?',
            'multiple' => true,
            'expanded' => true,
            'choices' => [
                'Session 1 - 11 au 12 mai ' => 1,
                'Session 2 - 13 au 15 mai ' => 2,
                'Session 3 - 16 au 18 mai ' => 3,
            ],
            'attr' => [
                'class' => 'my-checkbox-class'
            ]
        ])
        
        
        ->add('porterProjet', ChoiceType::class, [ 
            'label' => "Es tu porteur de projet ?",
            'placeholder' => '--',
            'mapped' => false,
            'choices'  => [
                   'Oui' => 1,
                   'Non' => 0,
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
