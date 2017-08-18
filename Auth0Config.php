<?php

return [
    'domain' => '{domain}.eu.auth0.com',
    'client_id' => '{client_id}',
    'client_secret' => '{client_secret}',
    'redirect_uri' => '{redirect_uri}', // return url
    'audience' => 'https://{domain}.eu.auth0.com/userinfo',
    'persist_id_token' => true,
    'persist_access_token' => true,
    'persist_refresh_token' => true,
];
