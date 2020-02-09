<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', TextType::class, [
                'label' => 'Votre nom',
                'required'=> true,
                'attr' => [
                    'placeholder'=> 'Entrez votre nom'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label'=> 'Votre commentaire',
                'required'=> true,
                'attr' => [
                    'placeholder'=> 'Entrez votre commentaire ici…',
                ]
            ])
            ->add('image', TextType::class, [
                'label'=> 'Image',
                'required'=> false,
                'attr' => [
                    'placeholder'=> 'Ajoutez une image à votre commentaire'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
