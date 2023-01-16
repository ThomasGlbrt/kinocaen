<?php

namespace App\Form;

use App\Entity\Inscrit;
use App\Entity\Utilisateur;
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

class InscritModifierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('poste', TextType::class)
            ->add('numTel', TelType::class)
            ->add('Talent', TextType::class, ['required' => false])
            ->add('ChosePlus', TextType::class, ['required' => false])
            ->add('image', FileType::class, ['required' => false])
            ->add('Utilisateurs', UtilisateurType::class, ['required' => false])
            ->add('metier', MetierType::class, ['required' => false])

            ->add('enregistrer', SubmitType::class, array('label' => 'Enregistrer'))

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
