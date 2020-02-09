<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'class'=> Category::class,
                'choice_label'=> 'name',
                'label'=> "Quelle catégorie ?"
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre de la recette',
                'required'=> true,
                'attr' => [
                    'placeholder'=> 'Entrez-le ici !'
                ]
            ])
            ->add('ingredient', TextareaType::class, [
                'label'=> 'Ingrédients',
                'required'=> true,
                'attr' => [
                    'placeholder'=> 'Ajoutez les ingrédients ici…',
                ]
            ])
            ->add('content', TextareaType::class, [
                'label'=> 'Description',
                'required'=> true,
                'attr' => [
                    'placeholder'=> 'Ajoutez le détail ici…',
                ]
            ])
            ->add('note', TextType::class, [
                'label'=> 'Difficulté / 5',
                'required'=> false,
                'attr' => [
                    'placeholder'=> 'Notez la recette sur 5'
                ]
            ])
            ->add('image', TextType::class, [
                'label'=> 'Image de la recette',
                'required'=> false,
                'attr' => [
                    'placeholder'=> 'Ajoutez une image à votre recette'
                ]
            ])
            ->add('imgTop', TextType::class, [
                'label'=> 'Image de couverture',
                'required'=> false,
                'attr' => [
                    'placeholder'=> 'Ajoutez une image à la une !'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
