<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Idea;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IdeaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre de l\'idée'])
            ->add('description')
            ->add('author')
            ->add('category', EntityType::class, [
                'class' => category::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository  $er) {
                return $er->createQueryBuilder('c')
                    ->orderBy('c.name', 'ASC');
                }
            ])
            ->add('isPublished')
            ->add('dateCreate')
            ->add('button', SubmitType::class, ['label' => 'Envoyer !'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Idea::class,
        ]);
    }
}
