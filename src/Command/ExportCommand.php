<?php

namespace App\Command;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

    protected function configure() {
        $this->setDescription('exporte les events en json');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
		for($jour = 0; $jour <= 7; $jour++) {
			$repository = $this->em->getRepository(Event::class);
			
			$date_auj = date('Y-m-') . (date('d') + $jour);
			
			$events = $repository->findBy(
				['date' => $date_auj]
			);
			
			$response = '';
			
			$c = count($events);
			
			foreach($events as $i => $event) {
				var_dump($event);	
				$response .= '{ "type": "Feature", "properties": { "id": "'.$event->getId().'", "mag": 2.3, "time": 1507425650893, "felt": null, "tsunami": 0 }, "geometry": { "type": "Point", "coordinates": [ '.$event->getLongitude().', '.$event->getLatitude().' ] } }';

				if($i+1 != $c){ // si c'est pas le dernier event, on ecrit avec une virgule
					$response = $response.', ';
				}
			}
			
			echo '{"type": "FeatureCollection","crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } },"features": ['.$response.']}'; // resultat

			
			$out = fopen(__DIR__.'/../../public/output/'.$date_auj.'.json', 'w');
			fwrite($out, '{"type": "FeatureCollection","crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } },"features": ['.$response.']}');// ecriture du resultat
			fclose($out);
		}
    }
}