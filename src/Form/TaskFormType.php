<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;
use App\Entity\Project;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TaskFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class
            )
            ->add(
                'description',
                TextareaType::class
            )
            ->add(
                'priority',
                IntegerType::class
            )
            ->add(
                'author',
                EntityType::class,
                array(
                    'class' => User::class,
                    'choice_label' => 'name'
                )
            )
            ->add(
                'project',
                EntityType::class,
                array(
                    'class' => Project::class,
                    'choice_label' => 'title'
                )
            
            )
        ;
        if($options['standalone']){
            $builder->add('submit',SubmitType::class);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'standalone' => false
        ]);
    }
}
