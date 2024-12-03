<?php

namespace App\Controller;

use App\Entity\Commentary;
use App\Entity\Wish;
use App\Form\CommentType;
use App\Form\WishType;
use App\Repository\CommentaryRepository;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/whish', name: 'whish_')]
class WhishController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findBy(["isPublish" => true], ["dateCreated" => "DESC"]);
        return $this->render('whish/list.html.twig', [
            'wishes' => $wishes
        ]);
    }

    #[Route('/detail/{id}', name: 'detail', requirements: ['id'=>'\d+'])]
    public function detail(#[MapEntity] Wish $wish, Request $request ,EntityManagerInterface $entityManager): Response
    {
        $commentary = new Commentary();
        $commentaries = $entityManager->getRepository(Commentary::class)->findBy(['wish' => $wish]);
        $commentForm = $this->createForm(CommentType::class, $commentary);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $commentary->setWish($wish);
            $commentary->setUser($this->getUser());
            $commentary->setDateCreated(new \DateTime());
            $entityManager->persist($commentary);
            $entityManager->flush();
            $this->addFlash('success', 'Commentary was added successfully!');
            return $this->redirectToRoute('whish_detail', ['id' => $wish->getId()]);
        }

        return $this->render('whish/detail.html.twig', [
            'wish' => $wish,
            'commentForm' => $commentForm,
            'commentaries' => $commentaries
        ]);
    }
    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager) : Response {
        $wish = new Wish();
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            $wish->setAuthor($this->getUser()->getUsername());
            $entityManager->persist($wish);
            $entityManager->flush();
            $this->addFlash('success', 'the wish has been created.');
            return $this->redirectToRoute('whish_detail', ['id' => $wish->getId()]);
        }

        return $this->render('whish/add.html.twig', [
            'wishForm' => $wishForm,
        ]);
    }
    #[Route('/update/{id}', name: 'update', requirements: ['id'=>'\d+'])]
    #[isGranted('ROLE_USER')]
    public function update(Request $request, EntityManagerInterface $entityManager, #[MapEntity] Wish $wish): Response {
        if($wish->getAuthor() == $this->getUser()->getUsername()){
           $wishForm = $this->createForm(WishType::class, $wish);
           $wishForm->handleRequest($request);
           if ($wishForm->isSubmitted() && $wishForm->isValid()) {
               $entityManager->persist($wish);
               $entityManager->flush();
               $this->addFlash('success', 'the wish has been updated.');
               return $this->redirectToRoute('whish_detail', ['id' => $wish->getId()]);
           }

           return $this->render('whish/update.html.twig', [
               'wishForm' => $wishForm,
           ]);
        }else{
            $this->addFlash('error', 'You can\'t update this wish.');
            return $this->redirectToRoute('whish_detail', ['id' => $wish->getId()]);
        }
    }
    #[Route('/delete/{id}', name: 'delete', requirements: ['id'=>'\d+'], methods: ['GET'])]
    #[isGranted('ROLE_USER', 'ROLE_ADMIN')]
    public function delete(Wish $wish, EntityManagerInterface $entityManager): Response {
        $entityManager->remove($wish);
        $entityManager->flush();
        $this->addFlash('success', 'the wish has been deleted.');
        return $this->redirectToRoute('whish_list');
    }
}
