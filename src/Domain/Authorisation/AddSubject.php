<?php


namespace Printdeal\Voyager\Domain\Authorisation;

use Printdeal\Voyager\Domain\Subject\SubjectId;
use Prooph\Common\Messaging\Command;

class AddSubject extends Command
{
    /**
     * @var AuthorisationId
     */
    private $authorisationId;

    /**
     * @var SubjectId
     */
    private $subjectId;

    /**
     * AddSubject constructor.
     * @param AuthorisationId $authorisationId
     * @param SubjectId $subjectId
     */
    private function __construct(AuthorisationId $authorisationId, SubjectId $subjectId)
    {
        $this->init();

        $this->authorisationId = $authorisationId;
        $this->subjectId = $subjectId;
    }

    /**
     * @param AuthorisationId $authorisationId
     * @param SubjectId $subjectId
     * @return AddSubject
     */
    public static function from(AuthorisationId $authorisationId, SubjectId $subjectId)
    {
        return new self($authorisationId, $subjectId);
    }

    /**
     * @return AuthorisationId
     */
    public function authorisation()
    {
        return $this->authorisationId;
    }

    /**
     * @return SubjectId
     */
    public function subject()
    {
        return $this->subjectId;
    }

    /**
     * @return array
     */
    public function payload()
    {
        return [
            Authorisation::AGGREGATE_NAME => (string)$this->authorisationId,
            SubjectWasAdded::PAYLOAD_SUBJECT => (string)$this->subjectId
        ];
    }

    /**
     * @param array $payload
     */
    protected function setPayload(array $payload)
    {
        $this->authorisationId = AuthorisationId::fromString($payload[Authorisation::AGGREGATE_NAME]);
        $this->subjectId = SubjectId::fromString($payload[SubjectWasAdded::PAYLOAD_SUBJECT]);
    }
}