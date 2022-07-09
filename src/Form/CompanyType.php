<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['placeholder' => 'JobPermut SARL'],
                'label' => 'Nom de votre entreprise'
                ])
            ->add('address', TextType::class, [
                'attr' => ['placeholder' => 'Orléans'],
                'label' => 'Ville de l\'entreprise'
                ])
            ->add('code', NumberType::class, [
                'attr' => ['placeholder' => '110294'],
                'label' => 'Code d\'invitation'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
