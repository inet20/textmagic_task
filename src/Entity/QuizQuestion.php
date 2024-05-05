<?php

namespace App\Entity;

use App\Repository\QuizQuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizQuestionRepository::class)]
#[ORM\Cache(usage: 'NONSTRICT_READ_WRITE', region: "region")]
class QuizQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private string $text;

    #[ORM\ManyToOne(targetEntity: Quiz::class, inversedBy: 'questions')]
    private Quiz $quiz;

    #[ORM\ManyToMany(targetEntity: QuizChoice::class, orphanRemoval: true, indexBy: 'id')]
    #[ORM\JoinTable(name: 'quiz_question_choices')]
    #[ORM\Cache(usage: 'NONSTRICT_READ_WRITE', region: "region")]
    private Collection $choices;

    #[ORM\ManyToMany(targetEntity: QuizChoice::class)]
    #[ORM\JoinTable(name: 'quiz_question_correct_choices')]
    #[ORM\Cache(usage: 'NONSTRICT_READ_WRITE', region: "region")]
    private Collection $correctChoices;

    #[ORM\Column(type: 'json', options: ['jsonb' => true])]
    private array $choiceOrder;

    public function __construct(string $text, array $choices, array $correctChoices)
    {
        $this->text = $text;
        $this->choices = new ArrayCollection($choices);
        $this->correctChoices = new ArrayCollection($correctChoices);

        $this->choiceOrder = [];
        foreach ($choices as $choice) {
            $this->choiceOrder[] = $choice->getId();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getChoices(): Collection
    {
        return $this->choices;
    }

    public function getCorrectChoices(): Collection
    {
        return $this->correctChoices;
    }

    /**
     * @return int[]
     */
    public function getChoiceOrder(): array
    {
        return $this->choiceOrder;
    }

    public function setQuiz(Quiz $quiz): self
    {
        if (isset($this->quiz)) {
            throw new \LogicException('Quiz already exists.');
        }

        $this->quiz = $quiz;

        return $this;
    }
}
