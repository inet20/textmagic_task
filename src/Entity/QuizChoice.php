<?php

namespace App\Entity;

use App\Repository\QuizChoiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizChoiceRepository::class)]
#[ORM\Cache(usage: 'NONSTRICT_READ_WRITE', region: "region")]
class QuizChoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function __toString(): string
    {
        return $this->text;
    }
}
