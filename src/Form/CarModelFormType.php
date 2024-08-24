<?php

namespace App\Form;

use App\Entity\CarBrand;
use App\Entity\CarModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class CarModelFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nombre del modelo:',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío.'])
                ]
            ])
            ->add('body_type', ChoiceType::class, [
                'required' => false,
                'label' => 'Carrocería:',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class' => 'form-select'
                ],
                'choices' => [
                    'Sedán' => 'Sedán',
                    'SUV' => 'SUV',
                    'Coupé' => 'Coupé',
                    'Convertible' => 'Convertible',
                    'Familiar' => 'Familiar',
                    'Monovolumen' => 'Monovolumen',
                    'Pick-up' => 'Pick-up',
                    'Roadster' => 'Roadster'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío.'])
                ]
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'label' => 'Imagen:',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'accept' => '.jpg, .jpeg, .png'
                ],
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Por favor, adjunte una imagen JPEG o PNG válida.'
                    ]),
                    new NotBlank(['message' => 'Este campo no puede estar vacío.'])
                ]
            ])
            ->add('carBrand', EntityType::class, [
                'class' => CarBrand::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => 'Marca:',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class' => 'form-select'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío.'])
                ]
            ])
            ->add('send', SubmitType::class, [
                'attr' => ['class' => 'btn btn-lg gradient-button']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CarModel::class,
        ]);
    }
}
