<?php


namespace Printdeal\Voyager\Domain\Authorisation;

use Printdeal\Voyager\Domain\Reviewer\ReviewerId;
use Prooph\Common\Messaging\Command;

class ApproveAuthorisation extends Command
{
    /**
     * @var AuthorisationId
     */
    private $authorisationId;

    /**
     * @var ReviewerId
     */
    private $reviewer;

    /**
     * ApproveAuthorisation constructor.
     * @param AuthorisationId $authorisationId
     * @param ReviewerId $reviewerId
     */
    private function __construct(AuthorisationId $authorisationId, ReviewerId $reviewerId)
    {
        $this->init();

        $this->authorisationId = $authorisationId;
        $this->reviewer = $reviewerId;
    }

    /**
     * @param AuthorisationId $authorisationId
     * @param ReviewerId $reviewerId
     * @return ApproveAuthorisation
     */
    public static function from(AuthorisationId $authorisationId, ReviewerId $reviewerId)
    {
        return new self($authorisationId, $reviewerId);
    }

    /**
     * @return AuthorisationId
     */
    public function authorisation()
    {
        return $this->authorisationId;
    }

    /**
     * @return ReviewerId
     */
    public function reviewer()
    {
        return $this->reviewer;
    }

    /**
     * @return array
     */
    public function payload()
    {
        return [
            Authorisation::AGGREGATE_NAME => (string)$this->authorisationId,
            AuthorisationWasApproved::PAYLOAD_REVIEWER => (string)$this->reviewer
        ];
    }

    /**
     * @param array $payload
     */
    protected function setPayload(array $payload)
    {
        $this->authorisationId = AuthorisationId::fromString($payload[Authorisation::AGGREGATE_NAME]);
        $this->reviewer = ReviewerId::fromString($payload[AuthorisationWasApproved::PAYLOAD_REVIEWER]);
    }
}