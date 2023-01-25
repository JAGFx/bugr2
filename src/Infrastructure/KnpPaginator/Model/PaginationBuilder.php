<?php

namespace App\Infrastructure\KnpPaginator\Model;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

class PaginationBuilder
{
    public static function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('page', NumberType::class, [
                'required' => false
            ]);
    }
}