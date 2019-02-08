<?php

namespace App\Command;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EventbriteCommand extends ContainerAwareCommand 
{
    protected static $defaultName = 'import:eventbrite';

    private $em;

    public function __construct(
        ?string $name = null,
        EntityManagerInterface $em
    ) {
        parent::__construct($name);
        $this->em = $em;
    }

    protected function configure() {
        $this->setDescription('importe le fichier');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
		
		$date_auj = date('Y').'-'.date('m').'-'.date('j');
		$date_demain = date('Y').'-'.date('m').'-'.(date('j') + 1);
        
        
		$r = file_get_contents('https://www.eventbriteapi.com/v3/events/search/?location.address=France&sort_by=distance&start_date.range_start='.$date_auj.'T17:00:00&start_date.range_end='.$date_demain.'T07:00:00&include_adult_events=on&token=XOUBJU4Z7YTN5F4TMTGE');
		
		$r = json_decode($r);
		
		for($i = 0; $i < count($r->events) ; $i++) {
			$time_start = microtime(true);
			$t = json_decode(
				file_get_contents(
					'https://www.eventbriteapi.com/v3/venues/'.$r->events[$i]->venue_id.'/?token=XOUBJU4Z7YTN5F4TMTGE'
				)
			);
		
			$event = new Event();
			
			$event->setLongitude($t->longitude);
			$event->setLatitude($t->latitude);
		
			$this->em->persist($event);
			
			echo (microtime(true) - $time_start).PHP_EOL;
			
			if (microtime(true) - $time_start >= 1) {
				echo 'flush';
				$this->em->flush();
			}
			
		}
		$this->em->flush();        
        echo microtime(true) - $time_start;
    }
}