<?php

namespace App\Utils\RateFetcher;

use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;

/**
 * Class RateFetcher
 * @package App\Utils\RateFetcher
 */
abstract class RateFetcher
{
    const DECIMAL_SEPARATOR = '.';

    /** @var string */
    protected $url;

    /** @var array */
    protected $ratesData;

    /** @var EntityManager */
    protected $entityManager;

    /**
     * RateFetcher constructor.
     * @param EntityManager $entityManager
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return $this
     */
    public function fetchRates()
    {
        $client = new Client();
        $this->ratesData = $client
            ->get($this->url)
            ->getBody();
        return $this;
    }

    /**
     * @return $this
     * @throws \Doctrine\DBAL\DBALException
     */
    public function resetRates()
    {
        $this->entityManager
            ->getConnection()
            ->prepare('delete from currency_rate')
            ->execute();
        return $this;
    }

    /**
     * @return $this
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persist()
    {
        $data = simplexml_load_string($this->ratesData);
        foreach ($this->getDataElements($data) as $dataElement) {
            $this->entityManager->persist(
                $this->getCurrencyRateFromDataElement($dataElement)
            );
        }
        $this->entityManager->flush();
        return $this;
    }

    /**
     * @param $data
     * @return mixed
     */
    abstract protected function getDataElements($data);

    /**
     * @param $dataElement
     * @return mixed
     */
    abstract protected function getCurrencyRateFromDataElement($dataElement);
}
