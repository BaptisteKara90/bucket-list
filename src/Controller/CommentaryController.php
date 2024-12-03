<?php

namespace App\Controller;

use App\Entity\Commentary;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/commentary', name: 'commentary_')]
class CommentaryController extends AbstractController
{
    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d+'])]
    public function update(Request $request, EntityManagerInterface $entityManager, Commentary $commentary): Response
    {
        $commentForm = $this->createForm(CommentType::class, $commentary);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $entityManager->persist($commentary);
            $entityManager->flush();
            $this->addFlash('success', 'Comment updated successfully!');
            return $this->redirectToRoute('whish_detail', ['id' => $commentary->getWish()->getId()]);
        }
        return $this->render('commentary/update.html.twig', [
            'commentary' => $commentary,
            'commentForm' => $commentForm,
        ]);
    }
    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'])]
    #[isGranted('ROLE_USER')]
    public function delete(Request $request, Commentary $commentary, EntityManagerInterface $entityManager): Response{
        $entityManager->remove($commentary);
        $entityManager->flush();
        $this->addFlash('success', 'Comment deleted successfully!');
        return $this->redirectToRoute('whish_detail', ['id' => $commentary->getWish()->getId()]);
    }
}
