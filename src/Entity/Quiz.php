<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
#[ORM\Cache(usage: 'NONSTRICT_READ_WRITE', region: "region")]
class Quiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity: QuizQuestion::class, mappedBy: 'quiz', cascade: ['persist'], orphanRemoval: true, indexBy: 'id')]
    #[ORM\Cache(usage: 'NONSTRICT_READ_WRITE', region: "region")]
    private Collection $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function addQuestion(QuizQuestion $question): self
    {
        $this->questions->add($question->setQuiz($this));

        return $this;
    }

    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function getLength(): int
    {
        return $this->questions->count();
    }
}
