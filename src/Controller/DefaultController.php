<?php

namespace App\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
	private $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }
	
    /**
     * @Route("/", name="map")
     */
    public function index()
    {
        return $this->render('map.html.twig');
    }
	
	/**
* @Route("/geo/{date}/{filters}", name="geo_json", methods={"GET"})
     */
    public function geo($date, $filters)
    {
		$repository = $this->em->getRepository(Event::class);

		$date = (string)$date;
		
		$events = $this->em->createQueryBuilder()
			->select('event.id', 'event.longitude', 'event.latitude')
			->from(Event::class, 'event')
			->where('event.date LIKE :date')
			->groupBy('event.title')
			->setParameter('date', '%'.$date.'%')
			->getQuery()
			->getArrayResult();

		$response = '';

		$c = count($events);

		foreach ($events as $i => $event) {
			if($event['longitude'] != null) {
				$response .= '{
								"type":"Feature",
								"properties":{
									"id":"'.$event['id'].'"
								},
								"geometry":{
									"type":"Point",
									"coordinates":
									[
										'.$event['longitude'].',
										'.$event['latitude'].'
									]
								}
							}';

				if ($i + 1 != $c){ // si c'est pas le dernier event, on une virgule
					$response = $response.', ';
				}
			}
		}
		
		$response = '{
			"type": "FeatureCollection",
			"crs": {
				"type": "name",
				"properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" }
			},
			"features": ['.$response.']
		}';
		
		$response = new Response($response);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
    }
}
