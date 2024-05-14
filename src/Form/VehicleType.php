<?php

namespace App\Form;

use App\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Sequentially;

class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', TextType::class, [
                'constraints' => new Sequentially(
                    [
                        new Length(['min' => 4, 'max' => 50, 'minMessage' => 'Minimum 4 caractères.', 'maxMessage' => 'Maximum 50 caractères.']),
                        new Regex(['pattern' => '/^[A-Za-z0-9_\-\s]{3,15}$/', 'message' => 'Caractères alphanumériques uniquement.']),
                        new NotBlank(),
                    ]
                ),
                'required' => true,
            ])
            ->add('model', TextType::class, [
                'constraints' => new Sequentially(
                    [
                        new Length(['min' => 4, 'max' => 50, 'minMessage' => 'Minimum 4 caractères.', 'maxMessage' => 'Maximum 50 caractères.']),
                        new Regex(['pattern' => '/^[A-Za-z0-9_\-\s]{3,15}$/', 'message' => 'Caractères alphanumériques uniquement.']),
                        new NotBlank(),
                    ]
                ),
                'required' => true,
            ])
            ->add('availabilities', CollectionType::class, [
                'entry_type' => AvailabilityType::class,
                'allow_add' => true,
                'by_reference' => false,
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
