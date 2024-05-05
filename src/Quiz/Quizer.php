<?php

namespace App\Quiz;

use App\DTO\QuizQuestionRating;
use App\DTO\QuizRating;
use App\Entity\Quiz;
use App\Entity\QuizAnswer;
use App\Entity\QuizQuestion;
use App\Entity\QuizResult;
use App\Factory\QuizAnswerFactory;
use App\Factory\QuizResultFactory;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class Quizer
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly QuizResultFactory $resultFactory,
        private readonly QuizAnswerFactory $answerFactory,
        private readonly QuizStorageInterface $quizStorage,
    )
    {

    }

    public function getActiveQuizAnswer(QuizResult $quizResult): QuizAnswer
    {
        $step = $this->quizStorage->getQuizStep() ?? 1;
        if ($step > $quizResult->getAnswers()->count()) {
            throw new \LogicException("Wrong quiz step");
        }

        return $quizResult->getAnswers()[$step - 1];
    }

    public function getQuizResult(): QuizResult
    {
        $quiz = $this->entityManager->getRepository(Quiz::class)->getLast();
        if ($quiz === null) {
            throw new \LogicException("There is no active quiz");
        }

        $resultId = $this->quizStorage->getQuizResultId();
        if ($resultId == null) {
            return $this->createQuizResult($quiz);
        } else {
            return $this->entityManager->getRepository(QuizResult::class)->getById($resultId) ?? $this->createQuizResult($quiz);
        }
    }

    public function resultForwards(QuizResult $result): void
    {
        $step = $this->getStep();
        $quizLength = $result->getQuiz()->getLength();

        if ($step > $quizLength) {
            throw new \LogicException("Cannot forward result with step $step");
        }

        if ($step == $quizLength) {
            $result->finalize();
        } elseif ($result->getAnswers()[$step] == null) {
            $nextQuestionId = $result->getQuestionOrder()[$step];
            $result->addAnswer($this->answerFactory->create($result->getQuiz()->getQuestions()[$nextQuestionId]));
        }

        $this->entityManager->flush();
        $this->quizStorage->setQuizStep(++$step);
    }

    public function resultBackwards(QuizResult $result): void
    {
        $step = $this->getStep();

        $currentStep = $this->quizStorage->getQuizStep() ?? 1;
        if ($currentStep < 1) {
            throw new \LogicException("Cannot backward result with step 1");
        }

        $this->quizStorage->setQuizStep(--$step);
    }

    public function getStep(): int
    {
        return $this->quizStorage->getQuizStep() ?? 1;
    }

    private function createQuizResult(Quiz $quiz): QuizResult
    {
        $result = $this->resultFactory->create($quiz);
        $this->entityManager->flush();

        $this->quizStorage->setQuizResultId($result->getId());

        return $result;
    }

    public function rateResult(QuizResult $result): QuizRating
    {
        $rating = new QuizRating();

        foreach ($result->getAnswers() as $answer) {
            $question = $answer->getQuestion();
            $rating->questions[$question->getId()] = $this->rateQuestion($question, $answer->getChoices());;
        }

        return $rating;
    }

    public function reset(): void
    {
        $this->quizStorage->clear();
    }

    private function rateQuestion(QuizQuestion $question, Collection $answerChoices): QuizQuestionRating
    {
        $choiceRatings = [];

        foreach ($question->getChoices() as $choice) {
            if (!$answerChoices->contains($choice)) {
                $choiceRatings[$choice->getId()] = null;
                continue;
            }

            $choiceRatings[$choice->getId()] = $question->getCorrectChoices()->contains($choice);
        }

        $questionRating = true;
        foreach ($choiceRatings as $choiceRating) {
            if ($choiceRating === false) {
                $questionRating = false;
                break;
            }
        }

        return new QuizQuestionRating($questionRating, $choiceRatings);
    }
}