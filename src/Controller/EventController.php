<?php

namespace App\Controller;

use App\Form\Model\SearchEvent;
use App\Form\SearchEventType;
use App\Helpers\ProcessData;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/events', name: 'event_')]
class EventController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProcessData $processData, Request $request): Response
    {
        $searchEvent = new SearchEvent();
        $searchEventForm = $this->createForm(SearchEventType::class, $searchEvent);
        $searchEventForm->handleRequest($request);

        $data = $processData->getData($searchEvent);

        return $this->render('event/index.html.twig',[
            'data' => $data,
            'searchEventForm' => $searchEventForm,
        ]);
    }
}
