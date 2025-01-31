<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TaskFormType extends AbstractType
{
    /**
     * buildForm
     *
     * @param  FormBuilderInterface $builder
     * @param  array                $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title', TextType::class,
                [
                    'label' => 'Titre',
                    'constraints' => [
                        new Length(
                            [
                                'min' => 2,
                                'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères.',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'content', TextareaType::class,
                [
                    'label' => 'Contenu',
                    'constraints' => [
                        new Length(
                            [
                                'min' => 2,
                                'minMessage' => 'Le contenu doit contenir au moins {{ limit }} caractères.',
                            ]
                        ),
                    ],
                ]
            );
    }

    /**
     * configureOptions
     *
     * @param  OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
