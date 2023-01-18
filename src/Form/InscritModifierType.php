<?php

namespace App\Form;

use App\Entity\Inscrit;
use App\Entity\Utilisateur;
use App\Entity\Metier;
use App\Form\UtilisateurType;
use App\Form\MetierType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;

class InscritModifierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('Utilisateurs', UtilisateurType::class, ['required' => false, 'label' => false])
            ->add('numTel', TelType::class)
            ->add('poste', TextType::class, ['required' => false]) 
            ->add('Talent', TextType::class, ['required' => false])
            ->add('ChosePlus', TextType::class, ['required' => false])
            ->add('metier', EntityType::class, [
                'class' => Metier::class,
                'choice_label' => function ($metier) {
                    return $metier->getNom();
                },
                'multiple' => true,
                'expanded' => true,
                'attr' => ['class' => 'form-input'],
                ])
            
            ->add('image', FileType::class, array('data_class' => null), ['required' => false,
                'constraints' => 
                new File([
                    'maxSize' => '1024k'])])  

        ;
    }


    public function getParent(){
        return InscritType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inscrit::class,
        ]);
    }
}

