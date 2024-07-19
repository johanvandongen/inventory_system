<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\ProductState;
use App\Entity\State;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductStateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('date', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('state', EntityType::class, [
                'class' => State::class,
                'choice_label' => 'state',
            ])
            ->add('description')
            // ->add('product', EntityType::class, [
            //     'class' => Product::class,
            //     'choice_label' => 'name',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductState::class,
        ]);
    }
}
