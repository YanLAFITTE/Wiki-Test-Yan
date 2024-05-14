<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de départ',
                'required' => true,
                'html5' => true,
                'attr' => ['min' => date('d-m-Y')],
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de retour',
                'required' => true,
                'html5' => true,
                'attr' => ['min' => date('d-m-Y')],
            ])
            ->add('maxPrice', NumberType::class, [
                'label' => 'Prix maximum (optionnel)',
                'required' => false,
                'constraints' => new PositiveOrZero([
                    'message' => 'Entrer un nombre positif ou zéro.'
                ]),
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null
        ]);
    }
}
