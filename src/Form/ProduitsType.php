<?php

namespace App\Form;

use App\Entity\Produits;
use App\Entity\Exposees;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('prix')
            ->add('ressource',FileType::class, array('data_class' => null,'required' => false))
            ->add('exposees', EntityType::class, [
                'class' => Exposees::class,
                'choice_label' => 'nom_e',
                'multiple' => false, // Set to false to ensure only one Exposees entity is selected
            ]);
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}
