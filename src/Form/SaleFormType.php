<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Sale;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class SaleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('price', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Precio',
                    'class' => 'form-control'
                ],
                'label' => 'Precio:',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío.']),
                ]
            ])
            ->add('delivery_date', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Fecha de entrega:',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío.'])
                ]
            ])
            ->add('payment_form', ChoiceType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-select',
                ],
                'label' => 'Método de pago:',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'choices' => [
                    'Efectivo' => 'Efectivo',
                    'Financiación' => 'Financiación',
                    'Tarjeta' => 'Tarjeta',
                    'Intercambio' => 'Intercambio',
                    'Renting' => 'Renting'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Este campo no puede estar vacío.'])
                ]
            ])
            ->add('comments', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Comentarios',
                    'class' => 'form-control'
                ],
                'label' => 'Comentarios:',
                'label_attr' => [
                    'class' => 'form-label'
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
            'data_class' => Sale::class,
        ]);
    }
}
