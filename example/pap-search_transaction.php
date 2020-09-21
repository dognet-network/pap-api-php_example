<?php

require_once __DIR__ . '/includes/_auth.php';

/** @var string $sessionId comes from _auth.php */
/** @var string $apiUrl comes from _auth.php */

/*
 * Search transactions
 */

// Initial request
$objTransactionGridRequest = [
    'C'        => 'Gpf_Rpc_Server',
    'M'        => 'run',
    'S'        => $sessionId,
    'requests' => [
        // Request #0
        // You can encapsulate multiple requests (arrays) as well
        [
            'C'        => 'Pap_Merchants_Transaction_TransactionsGrid', // will work only for merchants
            'M'        => 'getRows',
            'sort_col' => 'dateinserted',
            'sort_asc' => true, // desc is false,
            'offset'   => 0,
            'limit'    => 10,
            'filters'  => [
                // It's time to experiment here :)
                // Remove/change/add conditions as needed.

                // Format is one of:
                // ['column', 'operation', 'value'], // for single value
                // ['column', 'IN', ['array', 'of', 'values']], // for multiple values

                // Operations
                // Filter transactions by order ID
                ['orderId', LIKE, 'test231'], // for wildcard search use 'test%'

                // Filter transactions by campaign ID
                ['campaignid', EQUALS, '01234567'],

                // Filter transactions by status
                // P = pending, A = approved, D = declined
                ['rstatus', EQUALS, TRANSACTION_PENDING],

                // Filter transactions by multiple statuses
                ['rstatus', IN, [TRANSACTION_PENDING, TRANSACTION_DECLINED]],

                // Filter transactions by date, e.g. between 2020-01-01 00:00:00 and 2020-01-10 23:59:59
                ['dateinserted', DATE_EQUALS_GREATER, '2020-01-01'],
                ['dateinserted', DATE_EQUALS_LOWER, '2020-01-10'],

                // Filter by exact banner ID
                ['bannerid', EQUALS, '01234567'],

                // Filter by exact affiliate/publisher ID
                ['userid', EQUALS, '01234567'],

                // Filter by payout status
                // U = unpaid, P = paid
                ['payoutstatus', EQUALS, 'U'],
            ],

            /*
             * Which columns to return
             */
            'columns'  => [
                ['id'],
                ['commission'],
                ['totalcost'],
                ['t_orderid'], // this is original order ID provided by merchant
                ['dateinserted'],
                ['name'],
                ['campaignid'],
                ['commisionGroup'],
                ['rstatus'], // this is what determines conversion/transaction status (A = approved, P = pending, D = declined)
                ['firstname'],
                ['lastname'],
                ['userid'],
                ['userstatus'],
            ],
        ]
    ],
];

printf('Searching for transactions matching filters:' . PHP_EOL);
print_r($objTransactionGridRequest['requests'][0]['filters']);

// Encode to JSON
$jsonTransactionGridRequest = json_encode($objTransactionGridRequest);
// POST
$jsonTransactionGridResponse = post($jsonTransactionGridRequest, $apiUrl);

// Sample raw response is in general an array of arrays with transactions.

// Decode response to array
$objTransactionGridResponse = json_decode($jsonTransactionGridResponse, true);

// Get data for Request #0
$req0response = array_shift($objTransactionGridResponse);
$req0data = isset($req0response['rows']) ? $req0response['rows'] : [];
$req0header = array_shift($req0data); // first element is always header

printf('Got %d transaction(s)' . PHP_EOL, count($req0data));

// Traverse matching rows returned by Request #0
foreach ($req0data as $row) {
    printf('Transaction ID: %s' . PHP_EOL, $row[getColumnKey('id', $req0header)]);

    printf('Full transaction:' . PHP_EOL);
    print_r($row);
}

exit();