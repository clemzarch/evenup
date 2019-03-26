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

    protected function execute(InputInterface $input, OutputInterface $output) {
        for($jour = 0; $jour <= 7; $jour++) { // pour chaque jour a partir d’aujourdhui

            $date_auj = date('Y-m-') . (date('d') + $jour);

            echo $date_auj;

            $repository = $this->em->getRepository(Event::class);
            $events = $repository->findBy(
                ['date' => $date_auj]
            );

            $response = '';

            $c = count($events);

            foreach($events as $i => $event) {
                $response .='	{
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

                if($i+1 != $c){ // si c'est pas le dernier event, on ecrit avec une virgule
                    $response = $response.', ';
                }
            }

            $out = fopen(__DIR__.'/../../public/output/'.$date_auj.'.json', 'w');
            fwrite($out,'{"type":"FeatureCollection","features":['.$response.']}');// ecriture du resultat
            fclose($out);
        }
    }
}