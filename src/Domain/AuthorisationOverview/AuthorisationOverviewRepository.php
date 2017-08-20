<?php

namespace Printdeal\Voyager\Domain\AuthorisationOverview;

use Rhumsaa\Uuid\Uuid;

interface AuthorisationOverviewRepository
{
    public function get(Uuid $id): AuthorisationOverview;

    public function save(AuthorisationOverview $authorisation);
}