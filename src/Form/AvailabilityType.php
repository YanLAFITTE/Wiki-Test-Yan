<?php

namespace App\Form;

use App\Entity\Availability;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Sequentially;

class AvailabilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start_date',  DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner une date de début.']),
                ],
                'html5' => true,
                'attr' => ['min' => date('d-m-Y')],
            ])
            ->add('end_date',  DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner une date de fin.']),
                ],
                'html5' => true,
                'attr' => ['min' => date('d-m-Y')],
            ])
            ->add('price_per_day',  MoneyType::class, [
                'currency' => 'EUR',
                'scale' => 2,
                'required' => true,
                'constraints' => new Sequentially(
                    [
                        new NotBlank(['message' => 'Veuillez entrer un prix.']),
                        new PositiveOrZero([
                            'message' => 'Entrer un nombre positif ou zéro.'
                        ])
                    ],
                )
            ])
            ->add('status');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Availability::class,
        ]);
    }
}
