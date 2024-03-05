<?php

namespace App\Form;

use App\Entity\PlaceToVisit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File as FileConstraint;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

class PlaceToVisitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_lieu', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'The name cannot be blank']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'The name cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('description', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'The description cannot be blank']),
                ],
            ])
            ->add('address', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'The address cannot be blank']),
                ],
            ])
            ->add('cordonnee_geo', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'The coordinates cannot be blank']),
                ],
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new FileConstraint([
                        'maxSize' => '5M',
                        'maxSizeMessage' => 'The file is too large ({{ size }} {{ suffix }}). Maximum allowed size is {{ limit }} {{ suffix }}.',
                        'mimeTypes' => ['image/*'],
                        'mimeTypesMessage' => 'Please upload a valid image file.',
                    ]),
                    new Image([
                        'maxWidth' => 1200,
                        'maxHeight' => 1200,
                        'maxWidthMessage' => 'The image is too wide. Maximum allowed width is {{ max_width }}px.',
                        'maxHeightMessage' => 'The image is too high. Maximum allowed height is {{ max_height }}px.',
                    ]),
                ],
            ])
            ->add('destination', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'The destination cannot be blank']),
                ],
            ]);

        // Add view transformer to handle file uploads
        $builder->get('image')->addModelTransformer(new class implements DataTransformerInterface {
            public function transform($value): ?string
            {
                // If the value is already a File object, return its path
                if ($value instanceof File) {
                    return $value->getPathname();
                }

                return null;
            }

            public function reverseTransform($value): ?File
            {
                // If the value is a string (file path), create a File object from it
                if (is_string($value)) {
                    return new File($value);
                }

                return null;
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PlaceToVisit::class,
        ]);
    }
}
