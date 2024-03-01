<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cin', IntegerType::class, [
                'label' => 'CIN',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter your CIN']),
                ],
            ])
            ->add('tel', IntegerType::class, [
                'label' => 'Phone Number',
                'required' => false,
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
            ->add('nom', TextType::class, [
                'label' => 'First Name',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter your first name']),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Last Name',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter your last name']),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter your email']),
                    new Assert\Email(['message' => 'Please enter a valid email address']),
                    new Assert\Regex([
                        'pattern' => '/@esprit\.tn$/i',
                        'match' => false,
                        'message' => 'The email address cannot end with @esprit.tn',
                    ]),
                    new Assert\Callback([$this, 'validateEmailUnique']),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter your password']),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Your password must be at least {{ limit }} characters long',
                    ]),
                ],
            ])
            // You can add other fields here as needed
            ->add('save', SubmitType::class, [
                'label' => 'Save Changes',
                'attr' => ['class' => 'btn btn-primary'],
            ]);

        if (strpos($options['data']->getEmail(), '@esprit.tn') !== false) {
            $builder->remove('email');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    public function validateEmailUnique($value, ExecutionContextInterface $context)
    {
        $form = $context->getRoot();
        $email = $form->get('email')->getData();

        // Implement your logic to check if the email is unique within the form
        // You can access other form fields and perform any necessary checks

        // Example:
        // if ($emailExistsInForm) {
        //     $context->buildViolation('This email address is already in use in the form.')
        //         ->atPath('email')
        //         ->addViolation();
        // }
    }
}
