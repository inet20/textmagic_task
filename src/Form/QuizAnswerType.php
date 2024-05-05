<?php

namespace App\Form;

use App\Entity\QuizChoice;
use App\Entity\QuizAnswer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizAnswerType extends AbstractType
{
    public const PREV_BTN = 'prev';
    public const NEXT_BTN = 'next';

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuizAnswer::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('choices', EntityType::class, [
                'class' => QuizChoice::class,
                'multiple' => true,
                'expanded' => true,
                'choices' => $this->createChoices($builder),
            ])
            ->add(self::PREV_BTN, SubmitType::class)
            ->add(self::NEXT_BTN, SubmitType::class)
        ;
    }

    private function createChoices(FormBuilderInterface $builder): array
    {
        /** @var QuizAnswer $answer */
        $answer = $builder->getData();

        $choiceOrder = $answer->getChoiceOrder();
        $question = $answer->getQuestion();

        $choices = [];
        foreach ($choiceOrder as $choiceIndex) {
            $choice = $question->getChoices()[$choiceIndex];
            $choices[$choice->getId()] = $choice;
        }

        return $choices;
    }
}