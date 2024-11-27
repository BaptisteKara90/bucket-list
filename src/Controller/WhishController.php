<?php

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/whish', name: 'whish_')]
class WhishController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(WishRepository $wishRepository): Response
    {
        $whishes = $wishRepository->getWishesSortByDate();
        return $this->render('whish/list.html.twig', [
            'whishes' => $whishes
        ]);
    }

    #[Route('/detail/{id}', name: 'detail', requirements: ['id'=>'\d+'], methods: ['GET'])]
    public function detail( WishRepository $wishRepository,int $id ): Response
    {
        $whish = $wishRepository->find($id);
        return $this->render('whish/detail.html.twig', [
            'whish' => $whish
        ]);
    }
}
