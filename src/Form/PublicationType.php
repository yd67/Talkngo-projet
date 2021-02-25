<?php

namespace App\Form;

use App\Entity\Publication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PublicationType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('countryName',TextType::class, [
                'required' => true,
                'label' => 'Destination',
                                
                 
            ])
            ->add('date', DateType::class,[
                'required' => true,
                'label' => 'Date de départ',
                 
            ])
            ->add('duration', TextType::class,[
                'required' => true,
                'label' => 'Durée du séjour',
                 
            ])
            ->add('img', FileType::class,[
                'required' => true,
                'label' => 'Image du pays',
                 
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
        ]);
    }
}
