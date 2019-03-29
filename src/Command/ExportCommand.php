<?php

namespace App\Command;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use DateTime;
use DatePeriod;
use DateInterval;

class ExportCommand extends ContainerAwareCommand 
{
    protected static $defaultName = 'export:json';
    private $em;

    public function __construct(
        ?string $name = null,
        EntityManagerInterface $em
    ) {
        parent::__construct($name);
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        for ($jour = 0; $jour <= 7; $jour++) {
			$futureDay = date('Y-m-d', strtotime('+'.$jour.' day'));

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
										"id":"'.$event->getId().'"
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

            $out = fopen(__DIR__.'/../../public/output/'.$futureDay.'.json', 'w');
            fwrite($out,'{"type": "FeatureCollection","crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } },"features": ['.$response.']}'); // ecriture du resultat
            fclose($out);
        }
    }
}