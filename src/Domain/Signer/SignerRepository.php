<?php

namespace Printdeal\Voyager\Domain\Signer;

use Rhumsaa\Uuid\Uuid;

interface SignerRepository
{
    public function get(Uuid $id): Signer;

    public function save(Signer $signer);
}