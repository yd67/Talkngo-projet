<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UpdateAdminFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label' => 'nom',
                'required' => true,
            ])
            ->add('first_name',TextType::class,[
                'label' => 'prenom',
                'required' => true,
            ])

            ->add('age',BirthdayType::class,[
                'label' => 'date de naissance',
                'required' => true,
                'attr' => [
                    'class' => 'form-control js-datepicker'
                ],
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ]
                
                
                
            ])
            ->add('location',TextType::class,[
                'label' => 'ville',
            ])
           
            ->add('description',TextareaType::class,[
                'required' => true,
                'label' => 'description',
                 
            ])
            ->add('img', FileType::class, [

                'mapped' => false,
                'label' => 'Photo de profil',
                
            ])

            ->add('email',EmailType::class,[
                'label' => 'email',
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'vous devez accepter les conditions d\'utilisation .',
                    ]),
                ],
            ])
           
            
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
