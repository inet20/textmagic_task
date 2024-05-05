<?php

namespace App\DTO;

readonly class QuizQuestionRating
{
    public function __construct(
        public bool $rating,
        /** @var ?bool[] $choiceRatings */
        public array $choiceRatings,
    ) {
    }
}