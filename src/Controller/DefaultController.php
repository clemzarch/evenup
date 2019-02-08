<?php

namespace App\Controller;

use App\Entity\MyEntity;
use App\Form\MyEntityType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/default", name="default")
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
	
	/**
     * @Route("/lucky/number/{max}", name="lucky_number", requirements={"max"="\d+"})
     */
    public function numberAction($max)
    {
        $number = mt_rand(0, $max);

        return $this->render('default/number.html.twig', [
            'number' => $number
       ]);
    }
	
	/**
	*	@Route(
		"/blog/{_locale}/{year}/{title}", name="blog",
		requirements={
			"_locale"="en|fr",
			"year"="\d{4}",
			"title"="[a-z0-9-]+"
		},
		defaults={
			"_locale"="fr"
		}
		)
	*/
	public function BlogAction($_locale, $year, $title){
		return $this->render('default/blog.html.twig', [
			'_locale'	=> $_locale,
			'year'		=> $year,
			'title'		=> $title
       ]);
	}
	
	/**
	*@Route("/article", name="article_list")
	*/
	public function listArticleAction(){
		// return $this->render('default/blog.html.twig');
		$articles = $this->getDoctrine()->getRepository(MyEntity::class)->findAll();

		if (!$articles) {
			throw $this->createNotFoundException('No articles found');
		}

		foreach($articles as $article){
			return new Response('Check out this great product: <a href="article/'.$article->getId().'">'.$article->getTitle().'</a>');
		}
	}
	
	/**
	*@Route("/article/{id}", name="article_show", requirements={"id"="\d+"})
	*/
	public function showArticleAction($id){
		$article = $this->getDoctrine()
        ->getRepository(MyEntity::class)
        ->find($id);

		if (!$article) {
			throw $this->createNotFoundException(
				'No articles found for id '.$id
			);
		}

		return new Response('Check out this great product: <h1>'.$article->getTitle().'</h1>');
	}
	
	/**
	* @Route("/article/add", name="article_add")
	*/
	public function addArticleAction(Request $request){
		$article = new MyEntity();
		
		$form = $this->createForm(MyEntityType::class, $article);
			
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$article = $form->getData();
			$em = $this->getDoctrine()->getManager();
			$em->persist($article);
			$em->flush();

			return $this->redirectToRoute('article_show', array('id' => $article->getId()));
		}

		return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
        ));
	}
	
	/**
	* @Route("/article/update/{id}", name="article_update", requirements={"id"="\d+"})
	*/
	public function updateArticleAction($id, Request $request){
		$article = $this->getDoctrine()->getRepository(MyEntity::class)->find($id);
		
		$form = $this->createForm(MyEntityType::class, $article);
		
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$article = $form->getData();
			$em = $this->getDoctrine()->getManager();
			$em->persist($article);
			$em->flush();

			return $this->redirectToRoute('article_show', array('id' => $article->getId()));
		}

		return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
        ));
	}
	
	/**
	* @Route("/article/delete/{id}", name="article_delete", requirements={"id"="\d+"})
	*/
	public function deleteArticleAction($id, Request $request){
		$article = $this->getDoctrine()->getRepository(MyEntity::class)->find($id);
		
		if (!$article) {
			throw $this->createNotFoundException(
				'Y\'a pas d\'article '.$id
			);
		}
		
		$form = $this->createDeleteForm($article);
		
		// if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getEntityManager();
			$em->remove($article);
			$em->flush();
		// }
		
		return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
        ));
	}
	
	private function createDeleteForm(MyEntity $article)
    {
        //on crée un formulaire
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('article_delete', array('id' => $article->getId())))
            ->setMethod('DELETE')
            ->add('delete', SubmitType::class)
            ->getForm()
        ;
    }
}
