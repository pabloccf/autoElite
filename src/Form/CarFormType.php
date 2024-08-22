<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\CarModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('colour', ColorType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control form-control-color'
                ],
                'label' => 'Color:',
                'label_attr' => [
                    'class' => 'pe-2'
                ]
            ])
            ->add('plate', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Matrícula',
                    'class' => 'form-control'
                ],
                'label' => 'Matrícula:',
                'label_attr' => [
                    'class' => 'pb-2'
                ]
            ])
            ->add('vin', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'VIN',
                    'class' => 'form-control'
                ],
                'label' => 'VIN:',
                'label_attr' => [
                    'class' => 'pb-2'
                ]
            ])
            ->add('fuel', ChoiceType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-select',
                ],
                'label' => 'Combustible:',
                'label_attr' => [
                    'class' => 'pb-2'
                ],
                'choices' => [
                    'Gasolina' => 'Gasolina',
                    'Diésel' => 'Diésel',
                    'Electricidad' => 'Electricidad',
                    'Híbridos' => 'Híbridos'
                ]
            ])
            ->add('cylinders', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Cilindros',
                    'class' => 'form-control'
                ],
                'label' => 'Cilindros:',
                'label_attr' => [
                    'class' => 'pb-2'
                ]
            ])
            ->add('power', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Potencia',
                    'class' => 'form-control'
                ],
                'label' => 'Potencia:',
                'label_attr' => [
                    'class' => 'pb-2'
                ]
            ])
            ->add('kilometers', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Kilometros',
                    'class' => 'form-control'
                ],
                'label' => 'Kilometros:',
                'label_attr' => [
                    'class' => 'pb-2'
                ]
            ])
            ->add('doors', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Número de puertas',
                    'class' => 'form-control'
                ],
                'label' => 'Número de puertas:',
                'label_attr' => [
                    'class' => 'pb-2'
                ]
            ])
            ->add('status', ChoiceType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-select',
                ],
                'label' => 'Estado:',
                'label_attr' => [
                    'class' => 'pb-2'
                ],
                'choices' => [
                    'Nuevo' => 'Nuevo',
                    '2ª mano' => '2ª mano'
                ]
            ])
            ->add('previous_owners', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Número de dueños anteriores',
                    'class' => 'form-control'
                ],
                'label' => 'Número de dueños anteriores:',
                'label_attr' => [
                    'class' => 'pb-2'
                ]
            ])
            ->add('registration_date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('needs_repair', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'label' => 'Reparación:',
                'label_attr' => [
                    'class' => 'pe-2'
                ]
            ])
            ->add('extras', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Extras',
                    'class' => 'form-control'
                ],
                'label' => 'Extras:',
                'label_attr' => [
                    'class' => 'pb-2'
                ]
            ])
            ->add('manufacture_year', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Año de fabricación',
                    'class' => 'form-control'
                ],
                'label' => 'Año de fabricación:',
                'label_attr' => [
                    'class' => 'pb-2'
                ]
            ])
            ->add('carModel', EntityType::class, [
                'class' => CarModel::class,
                'choice_label' => 'name',
                'required' => false,
                'attr' => [
                    'class' => 'form-select'
                ],
                'label' => 'Modelo:',
                'label_attr' => [
                    'class' => 'pb-2'
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
            'data_class' => Car::class,
        ]);
    }
}
