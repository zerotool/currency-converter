<?php

namespace App\Command;

use App\Utils\CurrencyRatesProvider;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class CurrencyLoadRatesCommand
 * @package App\Command
 */
class CurrencyLoadRatesCommand extends Command
{
    protected static $defaultName = 'app:currency:load-rates';

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setDescription('Fetch latest currency rates')
            ->setHelp('This command allows to get latest currency rates');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $ratesProviderCode = $this->getRatesProviderCode();
            $currencyRatesProvider = new CurrencyRatesProvider($ratesProviderCode, $this->getEntityManager());
            $currencyRatesProvider
                ->getRateFetcher()
                ->fetchRates()
                ->resetRates()
                ->persist();
            $io->success('Rates are successfully loaded from ' . $ratesProviderCode);
        } catch (\Exception $exception) {
            $io->error('Unable to fetch rates: ' . $exception->getMessage());
        }
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this
            ->getApplication()
            ->getKernel()
            ->getContainer()
            ->get('doctrine')
            ->getEntityManager();
    }

    /**
     * @return mixed
     */
    private function getRatesProviderCode()
    {
        return $ratesProviderCode = $this
            ->getApplication()
            ->getKernel()
            ->getContainer()
            ->getParameter('app.currency.rates_provider');
    }
}
