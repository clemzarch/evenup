<?php

namespace App\Command;

use App\Entity\Poste;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportDataCommand extends ContainerAwareCommand 
{
    protected static $defaultName = 'app:import';

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
        $time_start = microtime(true);
        if (($handle = fopen("app/Resources/fixtures/data/elec.csv","r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $poste = new Poste();
                $poste->setCodeposte($data[0]);
                $poste->setNomposte($data[1]);
                $poste->setFonction($data[2]);
                $poste->setEtat($data[3]);
                $poste->setTension((integer)str_replace(['kV'], '', $data[4]));
                $poste->setLongitudeposteDD($data[5]);
                $poste->setLatitudeposteDD($data[6]);
                $this->em->persist($poste);


            }
            $this->em->flush();
            fclose($handle);
        }
        echo microtime(true) - $time_start;
    }
}