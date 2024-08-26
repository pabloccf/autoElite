<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['isEdit'];

        $builder
            ->add('name', TextType::class, [
                'required' => false,
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
                'required' => false,
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
                'required' => false,
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
                'required' => false,
                'attr' => [
                    'placeholder' => 'Teléfono',
                    'class' => 'form-control'
                ],
                'label' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío']),
                    new Regex([
                        'pattern' => '/^[6789]\d{8}$/',
                        'message' => 'Compruebe su número de teléfono'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Email',
                    'class' => 'form-control'
                ],
                'label' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío']),
                    new Email(['message' => 'Compruebe su email'])
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Contraseña',
                    'class' => 'form-control'
                ],
                'label' => false,
                'constraints' => array_merge(
                    $isEdit ? [] : [new NotBlank(['message' => 'Este campo no puede estar vacío.'])],
                    [
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Tu contraseña debe tener al menos {{ limit }} caracteres',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                        new Regex([
                            'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).*$/',
                            'message' => 'La contraseña debe contener al menos una letra mayúscula, una letra minúscula, un número y un carácter alfanumérico'
                        ])
                    ]
                )
            ])
            ->add('send', SubmitType::class, [
                'attr' => ['class' => 'btn btn-lg gradient-button']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'isEdit' => false,
        ]);
    }
}
