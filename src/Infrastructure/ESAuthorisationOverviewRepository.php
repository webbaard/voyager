<?php


namespace Printdeal\Voyager\Infrastructure;

use Printdeal\Voyager\Domain\Authorisation\Authorisation;
use Printdeal\Voyager\Domain\Authorisation\AuthorisationRepository;
use Printdeal\Voyager\Domain\AuthorisationOverview\AuthorisationOverview;
use Printdeal\Voyager\Domain\AuthorisationOverview\AuthorisationOverviewRepository;
use Prooph\EventStore\Aggregate\AggregateRepository;
use Rhumsaa\Uuid\Uuid;

class ESAuthorisationOverviewRepository implements AuthorisationOverviewRepository
{
    /**
     * @var AggregateRepository
     */
    private $aggregateRepository;

    /**
     * ESAuthorisationOverviewRepository constructor.
     * @param AggregateRepository $aggregateRepository
     */
    public function __construct(AggregateRepository $aggregateRepository)
    {
        $this->aggregateRepository = $aggregateRepository;
    }

    /**
     * @param Uuid $id
     * @return AuthorisationOverview|object
     */
    public function get(Uuid $id): AuthorisationOverview
    {
        return $this->aggregateRepository->getAggregateRoot((string)$id);
    }

    /**
     * @param AuthorisationOverview $authorisationOverview
     */
    public function save(AuthorisationOverview $authorisationOverview)
    {
        $this->aggregateRepository->addAggregateRoot($authorisationOverview);
    }
}