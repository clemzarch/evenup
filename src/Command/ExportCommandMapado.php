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

class ExportCommandMapado extends ContainerAwareCommand 
{
    protected static $defaultName = 'export:mapado:json';
    private $em;

    public function __construct(
        ?string $name = null,
        EntityManagerInterface $em
    ) {
        parent::__construct($name);
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $startDate = new \DateTime();
		$numberOfDays = 7;

		for ($i = 0; $i <= $numberOfDays; $i++) {
			$time_start = microtime(true);
			$futureDay = clone $startDate;
            $futureDay->add(new DateInterval("P{$i}D"));

            $repository = $this->em->getRepository(Event::class);
            foreach ($futureDay as $items) {
                $events = $repository->findBy(
                    ['date' => $items]
                );
            
            
            $response = '';

            $c = count($events);

            foreach ($events as $i => $event) {
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

            $out = fopen(__DIR__.'/../../public/output/'.$futureDay->format('Y-m-d').'.json', 'w');
            fwrite($out,'{"type": "FeatureCollection","crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } },"features": ['.$response.']}');// ecriture du resultat
            fclose($out);
        }
    }
    }
}