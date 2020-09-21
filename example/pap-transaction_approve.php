<?php

require_once __DIR__ . '/includes/_auth.php';

/** @var string $sessionId comes from _auth.php */
/** @var string $apiUrl comes from _auth.php */

/*
 * Change transactions' status
 */

// IDs of transactions we want to approve/decline.
// Example on how to search for these IDs are in ./pap-search_transaction.php
$transactionIds = ['01234567', '12345678'];
$desiredStatus = TRANSACTION_APPROVED;

printf('Updating status of %d transaction(s) to new status "%s"...' . PHP_EOL, count($transactionIds), $desiredStatus);

// Create request array
$objTransactionFormRequest = [
    'C'        => 'Gpf_Rpc_Server',
    'M'        => 'run',
    'S'        => $sessionId,
    'requests' => [
        // Request #0
        // You can encapsulate multiple requests (arrays) as well
        [
            'C'             => 'Pap_Merchants_Transaction_TransactionsForm', // will work only for merchants
            'M'             => 'changeStatus',
            'status'        => $desiredStatus, // here we set new status: A = approved, P = pending (default), D = declined
            'merchant_note' => '', // optional information for affiliate/publisher, e.g. reason for declining
            'send_email'    => 'N', // Y or N; whether to send notification email to affiliate/publisher including merchant_note
            'ids'           => $transactionIds, // IDs of transactions to which we're going to change the status
        ],
    ],
];

// Encode array to JSON
$jsonTransactionFormRequest = json_encode($objTransactionFormRequest);

// POST
$jsonTransactionFormResponse = post($jsonTransactionFormRequest, $apiUrl);

// Sample raw response in $jsonTransactionFormResponse will be similar to:
/*
[
    {
        "success":"Y",
        "finished":"Y",
        "errorMessage":"",
        "infoMessage":"Status of 1 selected Transaction was changed"
    }
]
*/

// Decode to array
$objTransactionFormResponse = json_decode($jsonTransactionFormResponse, true);

// Were there any error?
if (isset($objTransactionFormResponse[0]['success']) && $objTransactionFormResponse[0]['success'] !== 'Y') {
    die($objTransactionFormResponse[0]['message']);
}

// Display resulting message
echo $objTransactionFormResponse[0]['infoMessage'] . PHP_EOL;

exit();