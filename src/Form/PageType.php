<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Document;
use App\Entity\Page;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('contenu', TextareaType::class, [
                'required' => false,
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                "choice_label" => 'titre',
                'required' => false
            ])
            ->add('parent', EntityType::class, [
                'class' => Page::class,
                "choice_label" => 'titre',
                'required' => false
            ])
            ->add('auteur', TextType::class, [
                'required' => false
            ])
            ->add('documents', EntityType::class, [
                'class' => Document::class,
                "choice_label" => 'titre',
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('visible', CheckboxType::class, [
                'data' => true,
                'required' => false,
            ])
            ->add('createdAt', DateTimeType::class)
            ->add('date', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'empty_data' => null,
                'format' => 'dd/MM/yyyy',
                'constraints' => [
                    new NotBlank(['message' => "Veuillez renseigner une date"]),
                    new NotNull(['message' => "Veuillez renseigner une date"]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}
