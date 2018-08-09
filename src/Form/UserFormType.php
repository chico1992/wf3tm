<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Role;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name', 
                TextType::class,
                ['label'=> 'Give the user name']
            )
            ->add(
                'username', 
                TextType::class,
                ['label'=> 'Give the user a pseudonym']
            )
            ->add(
                'email', 
                EmailType::class,
                ['label'=> 'Email']
            )
            ->add(
                'roles', 
                EntityType::class,
                array(
                    'label' => 'Give the user privileges',
                    'class' => Role::class,
                    'expanded' => true,
                    'multiple' => true
                )
            )
            ->add(
                'password', 
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match',
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password')
                ]
            )
        ;
        if($options['standalone']){
            $builder->add(
                'submit', 
                SubmitType::class,
                ['attr'=> ['class'=>'btn-success btn-block']]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'standalone' => false,
        ]);
    }
}
