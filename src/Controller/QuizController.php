<?php

namespace App\Controller;

use App\Form\QuizAnswerType;
use App\Quiz\Quizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;

class QuizController extends AbstractController
{
    #[Route('/', name: 'app_quiz')]
    public function index(Request $request, Quizer $quizer, CacheInterface $cache): Response
    {
        $quizResult = $quizer->getQuizResult();

        if ($quizResult->isFinished()) {
            return $this->render('quiz/result.html.twig', [
                'length' => $quizResult->getQuiz()->getQuestions()->count(),
                'result' => $quizResult,
                'rating' => $quizer->rateResult($quizResult)
            ]);
        }

        $quizAnswer = $quizer->getActiveQuizAnswer($quizResult);

        $form = $this->createForm(QuizAnswerType::class, $quizAnswer);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $prevAction = $form->get(QuizAnswerType::PREV_BTN)->isClicked();

            if ($prevAction) {
                $quizer->resultBackwards($quizResult);
                return $this->redirectToRoute('app_quiz');
            } elseif ($form->isValid()) {
                $quizer->resultForwards($quizResult);
                return $this->redirectToRoute('app_quiz');
            }
        }

        return $this->render('quiz/index.html.twig', [
            'step' => $quizer->getStep(),
            'length' => $quizResult->getQuiz()->getQuestions()->count(),
            'question' => $quizAnswer->getQuestion(),
            'choice_order' => $quizAnswer->getChoiceOrder(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reset', name: 'app_quiz_reset')]
    public function reset(Request $request, Quizer $quizer): Response
    {
        $quizer->reset();

        return $this->redirectToRoute('app_quiz');
    }
}
