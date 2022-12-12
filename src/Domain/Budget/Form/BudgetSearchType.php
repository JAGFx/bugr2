<?php

namespace App\Domain\Budget\Form;

use App\Domain\Budget\Model\Search\BudgetSearchCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BudgetSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('from', DateTimeType::class, [
                'required' => false,
                'label' => 'Depuis',
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'html5' => true,
                'row_attr' => [
                    'class' => 'form-floating',
                ],
            ])
            ->add('to', DateTimeType::class, [
                'required' => false,
                'label' => "Jusqu'à",
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'html5' => true,
                'row_attr' => [
                    'class' => 'form-floating',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BudgetSearchCommand::class,
        ]);
    }
}
