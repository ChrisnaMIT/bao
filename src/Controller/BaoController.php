<?php

namespace App\Controller;

use App\Entity\Bao;
use App\Entity\Comment;
use App\Form\BaoType;
use App\Repository\BaoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class BaoController extends AbstractController
{
    #[Route('/bao', name: 'app_bao')]
    public function index(BaoRepository $baoRepository): Response
    {
            $baos= $baoRepository->findAll();

            return $this->render('bao/index.html.twig', [
                'baos' => $baos,
        ]);
    }

    #[Route('/bao/{id}', name: 'app_bao_show', priority: -1)]
    public function show(Bao $bao, Request $request, EntityManagerInterface $manager): Response
    {
        if (!$bao) {
            return $this->redirectToRoute('app_bao_index');
        }

        return $this->render('bao/show.html.twig', [
            'bao' => $bao,
        ]);
    }

    #[Route('/bao/{id}/delete', name :'bao_delete')]
    public function delete(Bao $bao, EntityManagerInterface $manager): Response
    {

        if($bao)
        {
            $manager->remove($bao);
            $manager->flush();
        }
        return $this->redirectToRoute('app_bao');
    }

    #[Route('/bao/{id}/edit', name: 'bao_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bao $bao, EntityManagerInterface $manager): Response
    {

        if(!$bao){
            return $this->redirectToRoute('bao/index.html.twig');
        }

        $baoForm =$this->createForm(BaoType::class, $bao);
        $baoForm->handleRequest($request);
        if($baoForm->isSubmitted() && $baoForm->isValid()){
            $manager->persist($bao);
            $manager->flush();
            return $this->redirectToRoute('app_bao');
        }

        return $this->render('bao/edit.html.twig', [
            'bao' => $baoForm->createView(),
        ]);

    }







}
