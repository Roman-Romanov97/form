<?php

namespace App\Form;

use App\Entity\Feedback;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class FeedbackType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $param1 = rand(1, 30);
        $param2 = rand(1, 30);
        $sum = $param1 + $param2;
        $builder
            ->add('text_feedback', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 4000]),]
                ]
            )
            ->add('user', UserType::class)
            ->add('captcha', IntegerType::class, ['mapped' => false, 'label' => "{$param1} + {$param2} ="])
            ->add('submit', SubmitType::class)
            ->add('result', HiddenType::class, ['mapped' => false, 'data' => $sum])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Feedback::class,
        ]);

    }

}
