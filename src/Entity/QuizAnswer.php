<?php

namespace App\Entity;

use App\Repository\QuizAnswerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuizAnswerRepository::class)]
#[ORM\Cache(usage: 'NONSTRICT_READ_WRITE', region: "region")]
class QuizAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: QuizQuestion::class)]
    private QuizQuestion $question;

    #[ORM\ManyToOne(targetEntity: QuizResult::class, inversedBy: 'answers')]
    private ?QuizResult $result = null;

    #[ORM\ManyToMany(targetEntity: QuizChoice::class, cascade: ['persist'])]
    #[ORM\Cache(usage: 'NONSTRICT_READ_WRITE', region: "region")]
    #[Assert\Count(min: 1, minMessage: "You must choose at least one answer")]
    private Collection $choices;

    #[ORM\Column(type: 'json', options: ['jsonb' => true])]
    private array $choiceOrder;

    public function __construct(QuizQuestion $question, ?array $choiceOrder)
    {
        $this->question = $question;
        $this->choices = new ArrayCollection();
        $this->choiceOrder = $choiceOrder;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): QuizQuestion
    {
        return $this->question;
    }

    public function getChoices(): Collection
    {
        return $this->choices;
    }

    public function getResult(): QuizResult
    {
        return $this->result;
    }

    public function getChoiceOrder(): array
    {
        return $this->choiceOrder;
    }

    public function setResult(QuizResult $result): self
    {
        $this->result = $result;

        return $this;
    }
}
