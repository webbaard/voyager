<?php


namespace Printdeal\Voyager\Infrastructure;

use Printdeal\Voyager\Domain\Authorisation\Authorisation;
use Printdeal\Voyager\Domain\Authorisation\AuthorisationRepository;
use Prooph\EventStore\Aggregate\AggregateRepository;
use Rhumsaa\Uuid\Uuid;

class ESAuthorisationRepository implements AuthorisationRepository
{
    /**
     * @var AggregateRepository
     */
    private $aggregateRepository;

    /**
     * ESAuthorisationRepository constructor.
     * @param AggregateRepository $aggregateRepository
     */
    public function __construct(AggregateRepository $aggregateRepository)
    {
        $this->aggregateRepository = $aggregateRepository;
    }

    /**
     * @param Uuid $id
     * @return Authorisation|object
     */
    public function get(Uuid $id): Authorisation
    {
        return $this->aggregateRepository->getAggregateRoot((string)$id);
    }

    /**
     * @param Authorisation $authorisation
     */
    public function save(Authorisation $authorisation)
    {
        $this->aggregateRepository->addAggregateRoot($authorisation);
    }
}