<?php

namespace App\Shared\Form;

use App\Domain\Budget\Entity\Budget;
use App\Shared\Model\Transfer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('budgetSource', EntityType::class, [
                'class'        => Budget::class,
                'required'     => false,
                'label'        => 'Origine',
                'choice_label' => 'name',
                'row_attr'     => [
                    'class' => 'form-floating',
                ],
            ])
            ->add('budgetTarget', EntityType::class, [
                'class'        => Budget::class,
                'required'     => false,
                'label'        => 'Cible',
                'choice_label' => 'name',
                'row_attr'     => [
                    'class' => 'form-floating',
                ],
            ])
            ->add('amount', MoneyType::class, [
                'label' => 'Valeur',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Transfer::class);
    }
}
