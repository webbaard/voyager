<?php


namespace Printdeal\Voyager\Config;


use Printdeal\Voyager\Domain\Authorisation\Authorisation;
use Printdeal\Voyager\Infrastructure\ESAuthorisationRepository;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\Aggregate\AggregateRepository;
use Prooph\EventStore\Aggregate\AggregateType;
use Prooph\EventStore\EventStore;

trait AuthorisationRepositoryProviding
{
    /**
     * @param EventStore $eventStore
     * @return ESAuthorisationRepository
     */
    private function getAuthorisationRepository(EventStore $eventStore)
    {
        // Repository
        $authorisationRepository = new ESAuthorisationRepository(
            new AggregateRepository(
                $eventStore,
                AggregateType::fromAggregateRootClass(Authorisation::class),
                new AggregateTranslator()
            )
        );
        return $authorisationRepository;
    }
}