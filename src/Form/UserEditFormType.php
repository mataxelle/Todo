<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Length;

class UserEditFormType extends AbstractType
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
            ->setMethod('PATCH')
            ->add(
                'name', TextType::class,
                [
                    'required' => false,
                    'constraints' => [
                        new Length(
                            [
                                'min'        => 2,
                                'minMessage' => 'Votre nom d\'utilisateur doit contenir au moins {{ limit }} caractères.',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'email', EmailType::class,
                [
                    'required' => false
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
        $resolver->setDefaults(['data_class' => User::class]);
    }
}
