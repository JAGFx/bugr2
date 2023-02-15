<?php

namespace App\Domain\PeriodicEntry\Form;

use App\Domain\Budget\Entity\Budget;
use App\Domain\PeriodicEntry\Entity\PeriodicEntry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PeriodicEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'    => 'Nom',
                'row_attr' => [
                    'class' => 'form-floating',
                ],
            ])
            ->add('amount', MoneyType::class, [
                'label' => 'Montant',
            ])
            ->add('executionDate', DateType::class, [
                'widget' => 'choice',
                'input'  => 'datetime_immutable',
            ])
            ->add('budgets', EntityType::class, [
                'class'        => Budget::class,
                'multiple'     => true,
                'expanded'     => false,
                'choice_label' => 'name',
                'required'     => false,
                'placeholder'  => '-- Pas de budget --',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', PeriodicEntry::class);
    }
}
