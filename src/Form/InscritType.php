<?php

namespace App\Form;

use App\Entity\Inscrit;
use App\Entity\TypeCompetences;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class InscritType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => false])
            ->add('prenom', TextType::class, [ 'label' => false])
            ->add('numTel', TextType::class, [ 'label' => false])
            ->add('facebook', TextType::class, [ 'label' => false ,'required' => false])
            ->add('Pays', TextType::class, [ 'label' => false])
            ->add('Ville', TextType::class, [ 'label' => false])
            ->add('PremierKabaret', ChoiceType::class, [
                'label' => 'Premier kabaret ?',
                'choices' => [
                    'Oui' => 1,
                    'Non' => 0,
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
                'data' => 0, // La valeur par défaut est 0 pour "Non"
            ])
            
            ->add('image', FileType::class, [
                'required' => false,
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '4000k',
                        'mimeTypes' => ['image/png', 'image/jpeg'],
                    ])
                ]
                ])
            ->add('LogementId', LogementType::class, ['label' => false])
            ->add('Session', SessionType::class)
            ->add('Competences', EntityType::class, [
                'class' => TypeCompetences::class,
                'label' => 'J\'ai les compétences pour :',
                'multiple' => true,
                'expanded' => false,
                'choice_label' => 'intitule',
                'attr' => ['class' => 'competences']
            ]) 

            ->add('Essai', EntityType::class, [
                'class' => TypeCompetences::class,
                'label' => 'J\'aimerais essayer :',
                'multiple' => true,
                'expanded' => false,
                'choice_label' => 'intitule',
                'mapped' => false,
                'attr' => ['class' => 'essais']
            ])             
            ->add('MatosDispo', TextType::class, ['label' => false])
            ->add('Permis', ChoiceType::class,[ 
                'label' => "Avez vous le premis ?",
                'attr' => ['class' => 'permis'],
                'placeholder' => '--',
                'choices'  => [
                       'Oui' => 1,
                       'Non' => 0,
                   ],])
            ->add('Vehicule', ChoiceType::class,[ 
                'label' => "Avez vous un vehicule ?",
                'required' => false,
                'placeholder' => '--',
                'choices'  => [
                       'Oui' => 1,
                       'Non' => 0,
                   ],])
            ->add('Session2', CheckboxType::class, [
                    'label' =>'Session 2',
                    'required' => false,
                    'value' => 1
                ])
            ->add('Session3', CheckboxType::class, [
                    'label' => 'Session 3',
                    'required' => false,
                    'value' => 2
                ])
            ->add('agreeTerm', CheckboxType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'agreeTerm-class'
                ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inscrit::class,
        ]);
    }
}
