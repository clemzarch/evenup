<?php

namespace App\Command;

use App\Entity\Event;
use App\Entity\MapadoIDs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use DateTime;
use DatePeriod;
use DateInterval;

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
		$startDate = new \DateTime();
		$numberOfDays = 7;

		for ($i = 0; $i <= $numberOfDays; $i++) {
			$time_start = microtime(true);
			$futureDay = clone $startDate;
			$futureDay->add(new DateInterval("P{$i}D"));
			
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.mapado.net/v2/activities?fields=@id,title,shortDate,firstDate,activityType,locale,description,address&itemsPerPage=1000&when=".$futureDay->format('Y-m-d')."&periodOfDay=evening",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
					"Authorization: Bearer MTMwZWJiODFiZjA4YTcyOGY2ZmMzMGYwOTQyYWM2NDZjODVlNDg1MzU0MzE3M2I4MTdiMDQyZjU5MDVkZjFjZA",
					"Cache-Control: no-cache",
					"Conent-Type: application/json",
					"Content-Type: application/x-www-form-urlencoded",
					"Postman-Token: 55672a19-0ffc-4fe6-a866-3e15c3df9dae"
				),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			$mapado_events = json_decode($response, JSON_PRETTY_PRINT);

			for ($i = 0; $i < count($mapado_events['hydra:member']); $i++) {
				$time_start = microtime(true);

				if ($mapado_events['hydra:member'][$i]['locale'] == 'fr' 
					&& \date('Y-m', strtotime($mapado_events['hydra:member'][$i]['firstDate'])) == '2019-03') {

					$mapado_id = $mapado_events['hydra:member'][$i]['@id'];
					$mapado_date = \date('Y-m-d', strtotime($mapado_events['hydra:member'][$i]['firstDate']));

					$result = $this->getContainer()
						->get('doctrine')
						->getRepository(MapadoIDs::class)
						->findOneBy(['mapado_id' => $mapado_id]);

					if ($result == null) {
						echo 'event existe pas, ajout en bdd'.PHP_EOL;
						$MapadoIDs = new MapadoIDs();
						$MapadoIDs->setMapadoId($mapado_id);
						$this->em->persist($MapadoIDs);

						$mapado = json_decode($response, JSON_PRETTY_PRINT);

						$event = new Event();

						$event->setLongitude($mapado['hydra:member'][$i]['address']['longitude']);
						$event->setLatitude($mapado['hydra:member'][$i]['address']['latitude']);
						$event->setTitle($mapado['hydra:member'][$i]['title']);
						$event->setDate($mapado_date);
						$event->setFormattedAddress($mapado['hydra:member'][$i]['address']['formattedAddress']);
						$event->setCity($mapado['hydra:member'][$i]['address']['city']);
						$event->setLocale($mapado['hydra:member'][$i]['locale']);
						$event->setActivityType($mapado['hydra:member'][$i]['activityType']);
						$event->setDescription($mapado['hydra:member'][$i]['description']);

						$this->em->persist($event);
					}
				}

			}
		}
		
		$this->em->flush();

		echo microtime(true) - $time_start.PHP_EOL;

		curl_close($curl);

		if ($err) {
			echo "cURL Error #: " . $err;
		} else {
			echo $response;
		}
		
	}
}