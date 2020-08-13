<?php

namespace App\Form;

use App\Entity\Document;
use App\Entity\Page;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('page', EntityType::class, [
                'class'        => Page::class,
                'choice_label' => 'titre',
                'multiple'     => false,
                'required'     => false
            ])
            ->add('fichier', FileType::class, [
                'required'    => false,
//                'constraints' => [
//                    new File([
//                        'mimeTypes'        => array_values(Document::LISTE_EXTENSIONS),
//                        'mimeTypesMessage' => "Format de fichier incorrect",
//                    ])
//                ],
            ])
            ->add('miniature', CheckboxType::class, [
                'data'     => false,
                'required' => false,
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
