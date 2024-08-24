<?php

namespace App\Form;

use App\Entity\CarBrand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class CarBrandFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nombre de la marca:',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío.'])
                ]
            ])
            ->add('logo', FileType::class, [
                'required' => false,
                'label' => 'Logo:',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'accept' => '.svg, .jpg, .jpeg, .png'
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío.']),
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/svg+xml'
                        ],
                        'mimeTypesMessage' => 'Por favor, adjunte una imagen SVG, JPEG o PNG válida.'
                    ])
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
            'data_class' => CarBrand::class,
        ]);
    }
}
