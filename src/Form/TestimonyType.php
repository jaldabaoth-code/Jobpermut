<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Testimony;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TestimonyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentary', TextareaType::class, [
                'attr' => ['placeholder' => 'Votre commentaire'],
                'label' => 'Votre commentaire'
            ])

            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'placeholder' => 'Choisissez votre utilisateur:',
                'empty_data' => null,
                'label' => 'Utilisateur'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Testimony::class,
        ]);
    }
}
