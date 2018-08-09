<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProjectFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add(
                'responsible', 
                EntityType::class,
                array(
                    'class' => User::class,
                    'choice_label' => 'name'
                )
            )->add(
                'worker', 
                EntityType::class,
                array(
                    'class' => User::class,
                    'choice_label' => 'name',
                    'expanded' => false,
                    'multiple' => true
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
            'data_class' => Project::class,
            'standalone' => false
        ]);
    }
}
