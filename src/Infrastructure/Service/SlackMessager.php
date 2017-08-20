<?php


namespace Printdeal\Voyager\Infrastructure\Service;


use Doctrine\DBAL\Driver\Connection;

class SlackMessager
{
    private $slackService;
    private $connection;

    public function __construct(SlackService $slackService, Connection $connection)
    {
        $this->slackService = $slackService;
        $this->connection = $connection;
    }

    public function sendMessage($userReference, $message)
    {
        try {
            $user = $this->connection->query('
	        SELECT slack_name 
            FROM user_overview 
            WHERE id = "' . $userReference . '"
        ')->fetch();
            $this->slackService->sendMessage(
                $user['slack_name'],
                $message
            );
        } catch(\Exception $exception){

        }
    }
}