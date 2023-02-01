<?php

namespace App\Domain\Entry\Form;

use App\Domain\Entry\Model\EntrySearchCommand;
use App\Infrastructure\KnpPaginator\Model\PaginationBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntrySearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        PaginationBuilder::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', EntrySearchCommand::class);
    }
}
