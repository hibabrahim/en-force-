<?php

namespace App\Form;

use App\Entity\Exposees;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Produits;
class ExposeesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_e')
            ->add('date_debut')
            ->add('date_fin')
            ->add('image_exposees',FileType::class, array('data_class' => null,'required' => false))
            ->add('produit_existant', EntityType::class, [
                'class' => Produits::class,
                'choice_label' => 'description',
                'multiple' => true,
            ]);
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exposees::class,
        ]);
    }
}
