<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ref')
            ->add('title')
            ->add('category',ChoiceType::class,[
                'choices'=>['Science-Fiction'=>'Science-Fiction','Mystery'=>'Mystery','Autobiography'=>'Autobiography'],
                'multiple'=>false,
                'expanded'=>false
            ])
            ->add('published', CheckboxType::class, [
                'label' => 'Published', 
                'required' => false, 
            ])
            ->add('Publication_date',DateType::class)
            ->add('Author',EntityType::class,[
                'class'=>Author::class,
                'choice_label'=>'username',
                'multiple'=>false
            ])
            ->add('Submit',SubmitType::class)//input type submit
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}