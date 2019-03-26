<?php

namespace App\Command;

use App\Entity\Event;
use App\Entity\MapadoIDs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MapadoCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'import:mapado';

    private $em;

    public function __construct(
        ?string $name = null,
        EntityManagerInterface $em
    ) {
        parent::__construct($name);
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        for ($jour = 0; $jour <= 7; $jour++) { // Pour chaque jours à partir d'aujourd'hui
			$time_start = microtime(true);

            $date_auj = date('Y-m-') . (date('d') + $jour);
            $date_demain = date('Y-m-') . (date('d') + $jour + 1);

            $d = json_decode('https://api.mapado.net/v2/activities?fields=title,firstDate,activityType,locale,shortDescription,address&itemsPerPage=1000&when=today&when=tomorrow&periodOfDay=evening');

			for ($i = 0; $i < count($d->events) ; $i++) {
				$venue_id = $d->events[$i]->venue_id;

				$result = $this->getContainer()
					->get('doctrine')
					->getRepository(EventbriteIDs::class)
					->findOneBy(['venue_id' => $venue_id]);

				if ($result == null) {
					echo 'event existe pas, ajout en bdd'.PHP_EOL;
					$EventbriteIDs = new EventBriteIDs();
					$EventbriteIDs->setVenueId($venue_id);
					$this->em->persist($EventbriteIDs);

					$t = json_decode(file_get_contents('https://www.eventbriteapi.com/v3/venues/'.$venue_id.'/?token=XOUBJU4Z7YTN5F4TMTGE'));
					$event = new Event();
					$event->setLongitude($t->longitude);
					$event->setLatitude($t->latitude);
					$event->setDate($date_auj);
					$this->em->persist($event);
				}
			}
			$this->em->flush();
			echo microtime(true) - $time_start.PHP_EOL;
        }
	}
}
