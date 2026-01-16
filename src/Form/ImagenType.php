<?php

namespace App\Form;

use App\Entity\Imagen;
use App\Entity\Categoria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; 
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ImagenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'nombre',
                FileType::class,
                [
                    'label' => 'Nombre imagen (JPG o PNG)',
                    'label_attr' => ['class' => 'etiqueta'],
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                            ],
                            'mimeTypesMessage' => 'Por favor, seleccione un archivo jpg o png',
                        ])
                    ],
                ]
            )

            ->add('categoria', EntityType::class, [
                'class' => Categoria::class
            ])
            ->add(
                'descripcion',
                TextType::class,
                [
                    'label' => 'Descripcion',
                    'required' => false,
                    'label_attr' => ['class' => 'etiqueta']
                ]
            )
            ->add(
                'numDownloads',
                NumberType::class,
                [
                    'label' => 'Numero de descargas',
                    'label_attr' => ['class' => 'etiqueta']
                ]
            )

            ->add(
                'numVisualizaciones',
                NumberType::class,
                [
                    'label' => 'Número de visualizaciones',
                    'label_attr' => ['class' => 'etiqueta']
                ]
            )
            ->add(
                'numLikes',
                NumberType::class,
                [
                    'label' => 'Numero de Likes',
                    'label_attr' => ['class' => 'etiqueta']
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Imagen::class,
        ]);
    }
}
