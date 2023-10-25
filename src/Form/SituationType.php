<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form;

use App\Entity\Situation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;


/**
 * Defines the form used to create and manipulate  situations.
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
final class SituationType extends AbstractType
{
    // Form types are services, so you can inject other services in them if needed
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // For the full reference of options defined by each form field type
        // see https://symfony.com/doc/current/reference/forms/types.html

        // By default, form fields include the 'required' attribute, which enables
        // the client-side form validation. This means that you can't test the
        // server-side validation errors from the browser. To temporarily disable
        // this validation, set the 'required' attribute to 'false':
        // $builder->add('title', null, ['required' => false, ...]);

        $builder
            ->add('explanation', TextareaType::class, [
                'label' => 'Explication'
            ])
            ->add('question', TextareaType::class, [
                'label' => 'Question',
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'delete_label' => 'Remove image',
                'asset_helper' => true,
                'download_uri' => false,
                'download_label' => 'Download image',
                'image_uri' => true,
                'imagine_pattern' => 'squared_thumbnail_small',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Situation::class,
        ]);
    }
}
