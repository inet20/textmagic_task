<?php

namespace App\Factory;

use App\Entity\Quiz;
use App\Entity\QuizResult;
use Doctrine\ORM\EntityManagerInterface;

class QuizResultFactory
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly QuizAnswerFactory $answerFactory,
    ) {

    }

    public function create(Quiz $quiz, bool $randomizeQuestions = true): QuizResult
    {
        $questionOrder = \array_keys($quiz->getQuestions()->toArray());
        if ($randomizeQuestions) {
            shuffle($questionOrder);
        }

        $result = new QuizResult($quiz, $questionOrder);
        $result->addAnswer($this->answerFactory->create($quiz->getQuestions()[$questionOrder[0]]));

        $this->entityManager->persist($result);

        return $result;
    }
}