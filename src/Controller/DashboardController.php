<?php

namespace App\Controller;

use App\Form\PromptType;
use App\Service\LmStudioService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function dashboard(Request $request, LmStudioService $lmStudioService): Response
    {
        $form = $this->createForm(PromptType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prompt = $form->getData()['prompt'];
            $response = $lmStudioService->response($prompt);

            return $this->render('prompt.html.twig', [
                'response' => $response
            ]);
        }

        return $this->render('index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}