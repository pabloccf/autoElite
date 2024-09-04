<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\CarBrand;
use App\Entity\CarModel;
use Doctrine\ORM\EntityRepository;
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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;

class CarFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $car = $builder->getData();
        $isEdit = $options['isEdit'];

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
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío.']),
                    new Regex([
                        'pattern' => '/^\d{4}\s?[BCDFGHJKLMNPRSTVWXYZ]{3}$/',
                        'message' => 'Formato de matrícula incorrecto. Formato correcto: 1234BCD.'
                    ])
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
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío.'])
                ]
            ])
            ->add('fuel', ChoiceType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-select',
                ],
                'label' => 'Combustible:',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'choices' => [
                    'Gasolina' => 'Gasolina',
                    'Diésel' => 'Diésel',
                    'Electricidad' => 'Electricidad',
                    'Híbridos' => 'Híbridos'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío.'])
                ]
            ])
            ->add('cylinders', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Número de cilindros',
                    'class' => 'form-control'
                ],
                'label' => 'Número de cilindros:',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío.'])
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
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío.'])
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
                    'class' => 'form-label'
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
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío.'])
                ]
            ])
            ->add('status', ChoiceType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-select',
                ],
                'label' => 'Estado:',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'choices' => [
                    'Nuevo' => 'Nuevo',
                    '2ª mano' => '2ª mano'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío.'])
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
                    'class' => 'form-label'
                ]
            ])
            ->add('registration_date', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Fecha de matriculación:',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío.'])
                ]
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
                    'class' => 'form-label'
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
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío.']),
                    new Range([
                        'min' => 1980,
                        'max' => date('Y'),
                        'notInRangeMessage' => 'Por favor, introduce un año entre el {{ min }} y el {{ max }}.'
                    ])
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
                    'class' => 'form-label'
                ],
                'choice_attr' => function (CarModel $carModel) {
                    return ['class' => 'brand-' . strtolower($carModel->getCarBrand()->getName())];
                },
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('cm')
                        ->where('cm.isDeleted = :isDeleted')
                        ->setParameter('isDeleted', 0)
                        ->groupBy('cm.name');
                },
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío.'])
                ]
            ])
            ->add('carBrand', EntityType::class, [
                'class' => CarBrand::class,
                'choice_label' => 'name',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-select'
                ],
                'label' => 'Marca:',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío.'])
                ],
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('cb')
                        ->where('cb.isDeleted = 0')
                        ->orderBy('cb.name', 'ASC');
                },
                'data' => $isEdit && $car && $car->getCarModel() ? $car->getCarModel()->getCarBrand() : null,
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
            'isEdit' => false,
        ]);
    }
}
