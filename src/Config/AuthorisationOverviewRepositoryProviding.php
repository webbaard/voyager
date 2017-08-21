<?php


namespace Printdeal\Voyager\Config;


use Printdeal\Voyager\Domain\Authorisation\Authorisation;
use Printdeal\Voyager\Domain\AuthorisationOverview\AuthorisationOverview;
use Printdeal\Voyager\Infrastructure\ESAuthorisationOverviewRepository;
use Printdeal\Voyager\Infrastructure\ESAuthorisationRepository;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\Aggregate\AggregateRepository;
use Prooph\EventStore\Aggregate\AggregateType;
use Prooph\EventStore\EventStore;

trait AuthorisationOverviewRepositoryProviding
{
    /**
     * @param EventStore $eventStore
     * @return ESAuthorisationOverviewRepository
     */
    private function getAuthorisationOverviewRepository(EventStore $eventStore)
    {
        // Repository
        $authorisationOverviewRepository = new ESAuthorisationOverviewRepository(
            new AggregateRepository(
                $eventStore,
                AggregateType::fromAggregateRootClass(AuthorisationOverview::class),
                new AggregateTranslator()
            )
        );
        return $authorisationOverviewRepository;
    }
}