<?php

namespace App\Form;

use App\Entity\Answer;
use App\Entity\Situation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content')
            ->add('valid', CheckboxType::class, ['label' => 'Estce la bonne réponse', 'required' => false])
            ->add('situation', EntityType::class, [
                'class' => Situation::class,
                'choice_label' => 'question',
                'placeholder' => 'Choose a situation'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Answer::class,
        ]);
    }
}
