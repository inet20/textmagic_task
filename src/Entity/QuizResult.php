<?php

namespace App\Entity;

use App\Repository\QuizResultRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizResultRepository::class)]
#[ORM\Cache(usage: 'NONSTRICT_READ_WRITE', region: "region")]
class QuizResult
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Quiz::class)]
    private Quiz $quiz;

    #[ORM\Column]
    private bool $finished = false;

    #[ORM\OneToMany(targetEntity: QuizAnswer::class, mappedBy: 'result', cascade: ['persist'], orphanRemoval: true)]
    #[ORM\Cache(usage: 'NONSTRICT_READ_WRITE', region: "region")]
    private Collection $answers;

    #[ORM\Column(type: 'json', options: ['jsonb' => true])]
    private array $questionOrder;

    public function __construct(Quiz $quiz, array $questionOrder)
    {
        $this->quiz = $quiz;
        $this->questionOrder = $questionOrder;
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function isFinished(): bool
    {
        return $this->finished;
    }

    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    /**
     * @return int[]
     */
    public function getQuestionOrder(): array
    {
        return $this->questionOrder;
    }

    public function addAnswer(QuizAnswer $answer): self
    {
        $this->answers->add($answer);
        $answer->setResult($this);

        return $this;
    }

    public function finalize(): void
    {
        if ($this->finished) {
            throw new \LogicException('Can not finalize twice');
        }

        $this->finished = true;
    }
}
