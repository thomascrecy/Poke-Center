<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Stock;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\GreaterThan;
use App\Entity\Category;

class SellType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez une catégorie',
                'choice_value' => 'id',
            ])
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('amount', IntegerType::class, [
                'label' => 'Quantité',
                'mapped' => false,
                'required' => true,
                'data' => 1,
                'constraints' => [
                    new Positive([
                        'message' => 'La quantité doit être un nombre positif.',
                    ]),
                    new GreaterThan([
                        'value' => 1,
                        'message' => 'La quantité doit être supérieure à 1.',
                    ])
                ],
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image de l\'article',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Merci d\'uploader une image valide (JPEG, PNG ou WEBP)',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
