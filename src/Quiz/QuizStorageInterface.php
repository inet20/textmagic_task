<?php

namespace App\Quiz;

interface QuizStorageInterface
{
    public function getQuizResultId(): ?int;
    public function getQuizStep(): ?int;
    public function setQuizResultId(int $quizResultId): void;
    public function setQuizStep(int $quizStep): void;
    public function clear(): void;
}