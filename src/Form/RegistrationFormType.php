<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Nombre',
                    'class' => 'form-control'
                ],
                'label' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío'])
                ]
            ])
            ->add('surname', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Apellidos',
                    'class' => 'form-control'
                ],
                'label' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío'])
                ]
            ])
            ->add('username', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Usuario',
                    'class' => 'form-control'
                ],
                'label' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío'])
                ]
            ])
            ->add('telephone', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Teléfono',
                    'class' => 'form-control'
                ],
                'label' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío']),
                    new Regex([
                        'pattern' => '/^[0-9-()+]{3,20}/',
                        'message' => 'Compruebe su n&uacute;mero de teléfono'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Email',
                    'class' => 'form-control'
                ],
                'label' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío']),
                    new Regex([
                        'pattern' => '/\S+@\S+\.\S+/',
                        'message' => 'Compruebe su email'
                    ])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Contraseña',
                    'class' => 'form-control'
                ],
                'label' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Tu contraseña debe tener al menos {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
