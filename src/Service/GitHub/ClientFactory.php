<?php
declare(strict_types=1);

namespace BugReport\Service\GitHub;

use BugReport\Service\Config;
use Github\Client;

class ClientFactory
{
    public function __invoke(Config $config) : Client
    {
        $client = new Client();

        if ($config->hasConfig()) {
            $client->authenticate($config->githubPersonalAccessToken(), null, Client::AUTH_HTTP_TOKEN);
        }

        return $client;
    }
}
