<?php

require_once __DIR__ . '/_functions.php';
require_once __DIR__ . '/_constants.php';

$sessionId = null;
$apiUrl = 'https://login.dognet.sk/scripts/server.php';

if (!is_file(__DIR__ . '/_credentials.php')) {
    print 'No _credentials.php found.' . PHP_EOL;
    print 'Please copy _credentials.sample.php into _credentials.php and fill it with credentials.' . PHP_EOL;

    exit(1);
}

$settings = require __DIR__ . '/_credentials.php';

// Create authentication array
$objLoginRequest = [
    'C'      => 'Pap_Api_AuthService',
    'M'      => 'authenticate',
    'fields' => [
        ['name', 'value', 'values', 'error'],
        ['username', 'USERNAME', null, $settings['username']], // username (email)
        ['password', 'PASSWORD', null, $settings['password']], // password
        ['roleType', $settings['roleType'], null, ''], // whether user is merchant (M) or affiliate/publisher (A)
        ['isFromApi', 'Y', null, ''], // always Y (yes)
        ['apiVersion', '', null, ''], // version hash code from PapApi.class.php file, can be empty
    ],
];

printf('Logging in...' . PHP_EOL);

print_r($objLoginRequest);
die;

// Encode auth array into JSON
$jsonLoginRequest = json_encode($objLoginRequest, JSON_PRETTY_PRINT);

// POST
$jsonLoginResponse = post($jsonLoginRequest, $apiUrl);

// Sample raw response in $jsonLoginResponse
/*
    {
        "fields": [
            ["name","value","values","error"],
            ["correspondsApi","N",null,""],
            ["username","my_username",null,""],
            ["password","",null,""],
            ["accountid","",null,""],
            ["rememberMe","N",null,""],
            ["language","en-US",null,""],
            ["S","YOUR_SESSION_ID",null,null],
            ["authToken","",null,""]
        ],
        "success":"Y",
        "message":"User authenticated. Logging in."
    }
*/

// Decode response to array
$objLoginResponse = json_decode($jsonLoginResponse, true);

// Unable to login?
if (isset($objLoginResponse['success']) && $objLoginResponse['success'] !== 'Y') {
    die($objLoginResponse['message']);
}

// Extract headers - those are always first
$header = array_shift($objLoginResponse['fields']);

// Traverse fields and search for 'S' (session)
foreach ($objLoginResponse['fields'] as $field) {
    if ($field[getColumnKey('name', $header)] === 'S') {
        // Set session ID into $sessionId so it can be passed to other files later on.
        $sessionId = $field[getColumnKey('value', $header)];
        break;
    }
    unset($field);
}

// No session ID found?
if ($sessionId === null) {
    die('Unable to find session ID within server response.');
}

printf('Logged in!' . PHP_EOL);
printf('Session ID: %s' . PHP_EOL, $sessionId);

// Do not pass unnecessary variables to other files
unset($objLoginRequest, $jsonLoginRequest, $jsonLoginResponse, $objLoginResponse);