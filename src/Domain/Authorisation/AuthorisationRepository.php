<?php

namespace Printdeal\Voyager\Domain\Authorisation;

use Rhumsaa\Uuid\Uuid;

interface AuthorisationRepository
{
    public function get(Uuid $id): Authorisation;

    public function save(Authorisation $authorisation);
}