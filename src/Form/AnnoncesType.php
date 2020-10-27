<?php

namespace App\Form;

use App\Entity\Annonces;
use App\Entity\Departement;
use App\Entity\Regions;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'label'=> 'Titre',
                'attr'=>[
                    'placeholder'=>'Un petit titre en quelques mots pour votre annonce'
                ]
            ])
            ->add('typeConcours')
            ->add('content', TextareaType::class,[
                'label'=>'Contenu de l\'annonce',
                'attr'=>[
                    'placeholder'=>'Le contenu de votre annonce !'
                ]
            ])

            ->add('image', FileType::class, [
                'data_class' => null,
                'label' =>'Photo de l\'affiche',
                'attr'=>[
                    'placeholder'=>'Selectionner une photo principale pour votre affiche !',
                ]
            ])
            ->add('description', TextType::class, [
                'mapped' => false,
                'required' => false,
                'attr'=>[
                    'placeholder'=>'Petite description pour la photo de votre affiche',
                ]
            ])
            ->add('adImages', FileType::class, [

                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'data_class' => null,
                'label'=> 'Photos pour le slide',
                'attr'=>[
                    'placeholder'=>'Selectionner 1 ou plusieurs pour photos affiche (4 max) !',
                ]

            ])
            ->add('region', EntityType::class,[
                'class' => Regions::class,
            ])
            ->add('departement',EntityType::class,[
                 'class'=> Departement::class,
//                    'placeholder'=>'Selectionner le departement !'
                ]
            )
            ->add('createdAt', DateTimeType::class,[
                'widget' => 'single_text',
                'label'=>'Date du concours',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
        ]);
    }
}
