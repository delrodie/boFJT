<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PresentationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'autocomplete'  => 'off',
                    'placeholder'   => 'Titre de l\'article'
                )
            ))
            ->add('rubrique', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'autocomplete'  => 'off',
                    'placeholder'   => 'rubrique'
                )
            ))
            ->add('contenu', TextareaType::class)
            ->add('motcle', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control tag-input',
                    'placeholder'   => 'Les mots clés',
                    'data-role' => "tagsinput",
                    'style' => 'color: #666'
                )
            ))
            ->add('statut', CheckboxType::class, array(
                'attr'  => array(
                    'class' => 'custom-control-input'
                ),
                'required' => false,
            ))
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'label' => '.',
            ])
            //->add('imageName')->add('imageSize')->add('updatedAt')->add('slug')->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Presentation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_presentation';
    }


}
