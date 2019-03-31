<?php

namespace App\Controller;

use App\Service\FormHistoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HistoryController extends AbstractController
{

    /**
     * @var FormHistoryService
     */
    private $formHistoryService;

    public function __construct(FormHistoryService $formHistoryService)
    {
        $this->formHistoryService = $formHistoryService;
    }

    /**
     * @Route("/history/{email}", name="history", requirements={"email"="[a-zA-Z\d]+\@[a-z]+\.[a-z]+"})
     */
    public function index($email)
    {
        $messages = $this->formHistoryService->getHistory($email);
        return $this->render('history/index.html.twig', [
            'messages' => $messages
        ]);
    }
}
