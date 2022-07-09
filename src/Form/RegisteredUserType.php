<?php

namespace App\Form;

use App\Entity\RegisteredUser;
use App\Entity\Rome;
use App\Repository\RomeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegisteredUserType extends AbstractType
{
    private RomeRepository $romeRepository;

    public function __construct(RomeRepository $romeRepository)
    {
        $this->romeRepository = $romeRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, ['attr' => ['placeholder' => 'John']])
            ->add('lastname', TextType::class, ['attr' => ['placeholder' => 'Doe']])
            ->add('phone', TextType::class, ['attr' => ['placeholder' => '06 56 86 98 09'], 'required' => false ])
            ->add('city', TextType::class, ['attr' => ['placeholder' => 'Orléans']])
            ->add('cityJob', TextType::class, ['attr' => ['placeholder' => 'Tours']])
            ->add('rome', EntityType::class, [
                'class' => Rome::class,
                'query_builder' => function () {
                    return $this->romeRepository->createQueryBuilder('r')
                        ->orderBy('r.name', 'ASC');
                },
                'choice_label' => 'name',
                'placeholder' => 'Choisissez votre métier:',
                'empty_data' => null,
            ])
            ->add('street', TextType::class, ['attr' => ['placeholder' => 'Rue des Lumières']])
            ->add('streetNumber', TextType::class, ['attr' => ['placeholder' => '67']])
            ->add('zipcode', TextType::class, ['attr' => ['placeholder' => '45100']])
            ->add('jobStreet', TextType::class, ['attr' => ['placeholder' => 'Avenue Charles Lenoir']])
            ->add('jobStreetNumber', TextType::class, ['attr' => ['placeholder' => '253']])
            ->add('jobZipcode', TextType::class, ['attr' => ['placeholder' => '37000']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegisteredUser::class,
        ]);
    }
}
