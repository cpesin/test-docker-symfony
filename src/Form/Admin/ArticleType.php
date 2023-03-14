<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * [Description ArticleType].
 */
class ArticleType extends AbstractType
{
    /**
     * [Description for buildForm].
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Texte',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('state', CheckboxType::class, [
                'label' => 'Etat',
            ])
            ->add('created', DateTimeType::class, [
                'label' => 'Date de création',
            ])
            ->add('updated', DateTimeType::class, [
                'label' => 'Date de modification',
            ])
            ->add('author', null, [
                'label' => 'Auteur',
            ])
        ;
    }

    /**
     * [Description for configureOptions].
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}