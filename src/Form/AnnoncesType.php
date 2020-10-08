<?php

namespace App\Form;

use App\Entity\Annonces;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('title')
            // ->add('slug')
            ->add('price', MoneyType::class)
            ->add('introduction', TextType::class)
            ->add('content', TextType::class,[
                'label'=>'Contenu de l\'article'
            ] )
            ->add('nombreAd', IntegerType::class,[
                'label'=>'Nombre d\'articles'
            ])
            ->add('image', FileType::class, [
                'data_class' => null,
                
            ])
           //->add('author')
            ->add('description', TextType::class, [

                'mapped' => false,
                'required' => false,

            ])
            ->add('adImages', FileType::class, [

                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'data_class' => null,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
        ]);
    }
}
