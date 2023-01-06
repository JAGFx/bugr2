<?php

namespace App\Domain\Budget\Form;

use App\Domain\Budget\Model\Search\BudgetSearchCommand;
use App\Shared\Utils\YearRange;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BudgetBalanceSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('year', ChoiceType::class, [
                'label' => 'Année',
                'choices' => $this->yearChoices(),
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

    private function yearChoices(): array
    {
        $years = YearRange::range(2019, YearRange::current());

        return array_combine($years, $years);
    }
}
