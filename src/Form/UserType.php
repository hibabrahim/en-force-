<?php
namespace App\Form;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cin', null, [
                'label' => 'CIN',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter your CIN']),
                ],
            ])
            ->add('tel', null, [
                'label' => 'Phone Number',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter your phone number']),
                    new Assert\Length([
                        'min' => 10,
                        'max' => 15,
                        'minMessage' => 'Your phone number must be at least {{ limit }} characters long',
                        'maxMessage' => 'Your phone number cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('nom', null, [
                'label' => 'Last Name',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter your last name']),
                ],
            ])
            ->add('prenom', null, [
                'label' => 'First Name',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter your first name']),
                ],
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Role',
                'choices' => [
                    'Admin' => 'admin',
                    'Tourist' => 'tourist',
                ],
                'placeholder' => 'Choose your role',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please select your role']),
                ],
            ])
            ->add('email', null, [
                'label' => 'Email',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter your email']),
                    new Assert\Email(['message' => 'Please enter a valid email address']),
                ],
            ])
            ->add('password', null, [
                'label' => 'Password',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter your password']),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Your password must be at least {{ limit }} characters long',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
