<?php

namespace App\QuizStorage;

use App\Quiz\QuizStorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class QuizSessionStorage implements QuizStorageInterface
{
    private const RESULT_ID_KEY = 'quiz_result_id';
    private const STEP_KEY = 'quiz_step';

    public function __construct(private readonly RequestStack $requestStack)
    {

    }

    public function getQuizResultId(): ?int
    {
        return $this->requestStack->getSession()->get(self::RESULT_ID_KEY);
    }

    public function getQuizStep(): ?int
    {
        return $this->requestStack->getSession()->get(self::STEP_KEY);
    }

    public function setQuizResultId(int $quizResultId): void
    {
        $this->requestStack->getSession()->set(self::RESULT_ID_KEY, $quizResultId);
    }

    public function setQuizStep(int $quizStep): void
    {
        $this->requestStack->getSession()->set(self::STEP_KEY, $quizStep);
    }

    public function clear(): void
    {
        $this->requestStack->getSession()->remove(self::RESULT_ID_KEY);
        $this->requestStack->getSession()->remove(self::STEP_KEY);
    }
}