<?php

namespace AppBundle\Form;

use AppBundle\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        // Id can be add like : {{ form_start(form, {'attr': {'id': 'form-id', 'name': 'form-name'}}) }}
        $builder
            ->add('fullName', TextType::class,[
                'label' =>'label.name'
            ])
            ->add('email', EmailType::class,[
                'label' => 'label.email'
            ])
            ->add('message', TextType::class,[
                 'label' => 'label.message'
            ])
            ->add('rating',IntegerType::class,
                ['label' => 'label.rating'])
            ->add('save', SubmitType::class, ['label' => 'Submit Review']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}