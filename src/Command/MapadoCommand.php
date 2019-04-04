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
		for ($jour = 0; $jour <= 7; $jour++) {
			$futureDay = date('Y-m-d', strtotime('+'.$jour.' days'));
			
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.mapado.net/v2/activities?fields=@id,title,shortDate,nextDate,activityType,locale,description,address&itemsPerPage=250&when=".$futureDay."&periodOfDay=evening",
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

				if ($mapado_events['hydra:member'][$i]['locale'] == 'fr') {

					$mapado_id = $mapado_events['hydra:member'][$i]['@id'];
					$mapado_date = \date('Y-m-d', strtotime($mapado_events['hydra:member'][$i]['nextDate']));

					$result = $this->getContainer()
						->get('doctrine')
						->getRepository(MapadoIDs::class)
						->findOneBy(['mapado_id' => $mapado_id]);

					if ($result == null) {
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
						// $event->setActivityType($mapado['hydra:member'][$i]['activityType']);
						
						$types = ['bar', 'discotheque', 'festival', 'concert', 'repas', 'spectacles'];
						$event->setActivityType($types[mt_rand(0, 5)]);
						
						$event->setDescription($mapado['hydra:member'][$i]['description']);

						$this->em->persist($event);						
					}
				}
			}
		}
		
		$this->em->flush();

		curl_close($curl);

		if ($err) {
			echo "cURL Error #: " . $err;
		}
	}
}