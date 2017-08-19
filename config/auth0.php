<?php

return [
    'domain' => '{domain}.eu.auth0.com',
    'client_id' => '{client}',
    'client_secret' => '{secret}',
    'redirect_uri' => '{local}/auth0/callback', // return url
    'audience' => 'https://{domain}.eu.auth0.com/userinfo',
    'persist_id_token' => true,
    'persist_access_token' => true,
    'persist_refresh_token' => true,
];
