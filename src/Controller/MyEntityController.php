<?php

namespace App\Controller;

use App\Entity\MyEntity;
use App\Form\MyEntityType;
use App\Repository\MyEntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/myentity")
 */
class MyEntityController extends AbstractController
{
    /**
     * @Route("/", name="my_entity_index", methods={"GET"})
     */
    public function index(MyEntityRepository $myEntityRepository): Response
    {
        return $this->render('my_entity/index.html.twig', [
            'my_entities' => $myEntityRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="my_entity_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $myEntity = new MyEntity();
        $form = $this->createForm(MyEntityType::class, $myEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			$file = $myEntity->getFile();
			$fileName = md5(uniqid()).'.'.$file->guessExtension();
			
			$file->move(
                $myEntity->getCoverUploadDirectory(),
                $fileName
            );
			
			$myEntity->setCover($fileName);
			
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($myEntity);
            $entityManager->flush();

            return $this->redirectToRoute('my_entity_index');
        }

        return $this->render('my_entity/new.html.twig', [
            // 'my_entity' => $myEntity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="my_entity_show", methods={"GET"})
     */
    public function show(MyEntity $myEntity): Response
    {
        return $this->render('my_entity/show.html.twig', [
            'my_entity' => $myEntity,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="my_entity_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MyEntity $myEntity): Response
    {
        $form = $this->createForm(MyEntityType::class, $myEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('my_entity_index', [
                'id' => $myEntity->getId(),
            ]);
        }

        return $this->render('my_entity/edit.html.twig', [
            'my_entity' => $myEntity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="my_entity_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MyEntity $myEntity): Response
    {
        if ($this->isCsrfTokenValid('delete'.$myEntity->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($myEntity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('my_entity_index');
    }
}
