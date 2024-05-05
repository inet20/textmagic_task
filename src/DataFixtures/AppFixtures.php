<?php

namespace App\DataFixtures;

use App\Entity\QuizChoice;
use App\Entity\Quiz;
use App\Entity\QuizQuestion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private ObjectManager $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $var0 = $this->createChoice('0');
        $var1 = $this->createChoice('1');
        $var2 = $this->createChoice('2');
        $var3 = $this->createChoice('3');
        $var4 = $this->createChoice('4');
        $var5 = $this->createChoice('5');
        $var6 = $this->createChoice('6');
        $var8 = $this->createChoice('8');
        $var9 = $this->createChoice('9');
        $var10 = $this->createChoice('10');
        $var12 = $this->createChoice('12');
        $var14 = $this->createChoice('14');
        $var16 = $this->createChoice('16');
        $var18 = $this->createChoice('18');
        $var20 = $this->createChoice('20');

        $var0p8 = $this->createChoice('0 + 8');
        $var1p5 = $this->createChoice('1 + 5');
        $var2p4 = $this->createChoice('2 + 4');
        $var2p16 = $this->createChoice('2 + 16');
        $var3p1 = $this->createChoice('3 + 1');
        $var5p7 = $this->createChoice('5 + 7');
        $var17p1 = $this->createChoice('17 + 1');

        $this->manager->flush();

        $question1 = new QuizQuestion(
            '1 + 1 =',
            [$var3, $var2, $var0],
            [$var2]
        );
        $question2 = new QuizQuestion(
            '2 + 2 =',
            [$var4, $var3p1, $var10],
            [$var4, $var3p1]
        );
        $question3 = new QuizQuestion(
            '3 + 3 =',
            [$var1p5, $var1, $var6, $var2p4],
            [$var1p5, $var6, $var2p4]
        );
        $question4 = new QuizQuestion(
            '4 + 4 =',
            [$var8, $var4, $var0, $var0p8],
            [$var8, $var0p8]
        );
        $question5 = new QuizQuestion(
            '5 + 5 =',
            [$var6, $var18, $var10, $var9, $var0],
            [$var10]
        );
        $question6 = new QuizQuestion(
            '6 + 6 =',
            [$var3, $var9, $var0, $var12, $var5p7],
            [$var12, $var5p7]
        );
        $question7 = new QuizQuestion(
            '7 + 7 =',
            [$var5, $var14],
            [$var14]
        );
        $question8 = new QuizQuestion(
            '8 + 8 =',
            [$var16, $var12, $var9, $var5],
            [$var16]
        );
        $question9 = new QuizQuestion(
            '9 + 9 =',
            [$var18, $var9, $var17p1, $var2p16],
            [$var18, $var17p1, $var2p16]
        );
        $question10 = new QuizQuestion(
            '10 + 10 =',
            [$var0, $var2, $var8, $var20],
            [$var20]
        );

        $quiz = new Quiz();
        $quiz
            ->addQuestion($question1)
            ->addQuestion($question2)
            ->addQuestion($question3)
            ->addQuestion($question4)
            ->addQuestion($question5)
            ->addQuestion($question6)
            ->addQuestion($question7)
            ->addQuestion($question8)
            ->addQuestion($question9)
            ->addQuestion($question10);

        $manager->persist($quiz);
        $manager->flush();
    }

    private function createChoice(string $choiceText): QuizChoice
    {
        $choice = new QuizChoice($choiceText);
        $this->manager->persist($choice);

        return $choice;
    }
}
