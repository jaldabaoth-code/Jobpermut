<?php

namespace App\Form;

use App\Entity\Subscription;
use App\Service\ApiRome\ApiRome;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriptionType extends AbstractType
{
    private ApiRome $apiRome;

    public function __construct(ApiRome $apiRome)
    {
        $this->apiRome = $apiRome;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = $options['rome'] ?
        $this->apiRome->sortResponseByName($this->apiRome->getAppelationsByJob($options['rome'])) :
        ['Votre profession n\'est pas définie' => null];

        $builder
            ->add('curriculumFile', VichFileType::class, [
                'label' => 'Curriculum',
                'required' => false
            ])
            ->add('jobDescriptionFile', VichFileType::class, [
                'label' => 'Fiche de poste',
                'required' => false,
            ])
            ->add('ogrCode', ChoiceType::class, [
                'choices' => $choices,
                'label' => 'Spécialité Métier',
                'placeholder' => 'Choisissez votre spécialité:',
                'empty_data' => null,
                'required' => false,
            ])
            ->add('companyCode', TextType::class, [
                'label' => 'Code Entreprise',
                'required' => false,
                'attr' => ['placeholder' => 'Entrez votre code entreprise'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subscription::class,
            'rome' => null,
        ]);
    }
}
