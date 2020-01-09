<?php

namespace App\Command;

use App\Repository\CitationRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RandomQuoteCommand extends Command
{
    private $citationRepository;

    public function __construct(CitationRepository $citationRepository)
    {
        $this->citationRepository = $citationRepository;

        parent::__construct();
    }

    protected static $defaultName = 'app:random-quote';

    protected function configure()
    {
        $this
            ->setDescription('Get a random quote')
            ->addOption(
                'category',
                null,
                InputOption::VALUE_OPTIONAL,
                'How many times should the message be printed?',
                )
            ->setHelp('Récupère et affiche une quote en base de données')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $quote = $this->citationRepository->getRandomquote()[0];
        $io->title('Random Quote');

        if ($input->getOption('category')) {
            $quote = $this->citationRepository->getbyCatg($input->getOption('category'));
            if (count($quote) > 0) {
                $quote = $quote[0];
            } else {
                $io->warning('Aucune citation trouvé pour la catégorie '.$input->getOption('category').' !');

                return 0;
            }
        }

        if ($quote) {
            $io->text([$quote->getContent(), $quote->getMeta()]);
        } else {
            $io->warning('Aucune citation trouvé !');
        }

        return 0;
    }
}
