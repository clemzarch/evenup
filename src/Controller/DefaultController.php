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
        return $this->render('map.html');
    }
	
	/**
     * @Route("/geo/{year}-{month}-{day}", name="geo_json", methods={"GET"})
     */
    public function geo($year, $month, $day)
    {
		$futureDay = $year.'-'.$month.'-'.$day;

		$repository = $this->em->getRepository(Event::class);

		$events = $repository->findBy(
			['date' => $futureDay]
		);
		
		$response = '';

		$c = count($events);

		foreach ($events as $i => $event) {
			if($event->getLongitude() != null) {
				$response .= '{
								"type":"Feature",
								"properties":{
									"id":"'.$event->getId().'",
									"type":"concert..."
								},
								"geometry":{
									"type":"Point",
									"coordinates":
									[
										'.$event->getLongitude().',
										'.$event->getLatitude().'
									]
								}
							}';

				if ($i + 1 != $c){ // si c'est pas le dernier event, on ecrit avec une virgule
					$response = $response.', ';
				}
			}
		}

	//	$out = fopen(__DIR__.'/../../public/output/'.$futureDay.'.json', 'w');
		
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
