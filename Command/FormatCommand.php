<?php

namespace Decline\TransformatBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class FormatCommand
 * @package Decline\TransformatBundle\Command
 */
class FormatCommand extends ContainerAwareCommand
{

    /**
     * Configuration for the FormatCommand
     */
    protected function configure()
    {
        $this
            ->setName('transformat:format')
            ->setDescription('Format translation files.')
            ->setHelp('Formats the configured set of translation files and performs some validity checks.')
        ;
    }

    /**
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Decline Transformat Bundle');

        $io->text('Starting to format translation files:');

        $errors = $this->getContainer()->get('decline_transformat.format')->format($io);

        $io->newLine();

        if (count($errors)) {
            $io->error($errors);
        } else {
            $io->success('Done.');
        }
    }
}