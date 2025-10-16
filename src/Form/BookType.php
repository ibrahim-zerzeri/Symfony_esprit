<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('publicationDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'username',
                'required' => false,
                'placeholder' => 'Choose an author',
            ])
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Fiction' => 'Fiction',
                    'Non-Fiction' => 'Non-Fiction',
                    'Science' => 'Science',
                    'Technology' => 'Technology',
                    'History' => 'History',
                    'Biography' => 'Biography',
                    'Fantasy' => 'Fantasy',
                    'Mystery' => 'Mystery',
                    'Romance' => 'Romance',
                    'Children' => 'Children',
                    'Other' => 'Other'
                ],
                'placeholder' => 'Choose a category',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}