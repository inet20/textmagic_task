<?php

namespace App\Factory;

use App\Entity\QuizAnswer;
use App\Entity\QuizQuestion;

class QuizAnswerFactory
{
    public function create(QuizQuestion $quizQuestion, bool $randomizeChoices = true): QuizAnswer
    {
        $choiceOrder = \array_keys($quizQuestion->getChoices()->toArray());
        if ($randomizeChoices) {
            shuffle($choiceOrder);
        }

        return new QuizAnswer($quizQuestion, $choiceOrder);
    }
}