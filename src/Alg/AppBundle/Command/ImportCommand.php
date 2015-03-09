<?php

namespace Alg\AppBundle\Command;

use Alg\AppBundle\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('persons:import')
            ->setDescription('Import persons')
            ->addArgument('csv', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $csv = $input->getArgument('csv');

        if(!is_readable($csv)){
            $output->writeln('File is not readable !');
            return;
        }

        $file = fopen($csv, 'r');

        $firstline = true;
        while($row = fgetcsv($file)) {
            if ($firstline) {
                $firstline = false;
                continue;
            }

            $person = new Person();
            $form = $this->getContainer()->get('form.factory')->create('person', $person);

            $form->submit([
                'firstname' => $row[0],
                'lastname' => $row[1],
                'company_name' => $row[2],
                'address' => $row[3],
                'city' => $row[4],
                'country' => $row[5],
                'postal' => $row[6],
                'phone1' => $row[7],
                'phone1' => $row[8],
                'email' => $row[9],
                'web' => $row[10],
                'siren' => '',
            ]);

            $em = $this->getContainer()->get('doctrine')->getManager();

            $em->persist($person);
            $em->flush();

            $output->writeln($row[1]);
        }

    }

}