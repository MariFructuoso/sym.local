<?php

namespace App\Form;

use App\Entity\Imagen;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ImagenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'nombre', 
                TextType::class,
                [
                    'label' => 'Nombre: ',
                    'required' => true,
                    'label_attr' => ['class' => 'etiqueta']
                ]
            )
            ->add(
                'categoria',
                NumberType::class,
                [
                    'label' => 'Categoria',
                    'label_attr' => ['class' => 'etiqueta']
                ]
                )
            ->add(
                'descripcion',
                TextType::class,
                [
                    'label' => 'Descripcion',
                    'required' => false,
                    'label_attr' => ['class' => 'etiqueta']
                ]
                )
            ->add('numDownloads',
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
                    'label'=>'Número de visualizaciones',
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
