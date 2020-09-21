# Post Affiliate Pro (PAP) PHP Example

## Overview
 This example was written in an extremely simple way so even non-PHP developers could understand it.
 Basically we're only creating a bunch of arrays, POST-ing them to PAP's API as a JSONs and iterating over the
 response. Please check out the files in `./example/` folder. 
 
 Note that you can always read more in an official [Knowledge base](https://support.qualityunit.com/712031-API).
 
## Download this example
 You don't need to use composer. Just clone this repo like so:
 
 ```sh
$ mkdir /var/www/pap-api-php_example
$ git clone https://github.com/dognet-network/pap-api-php_example.git
 ```

## Initial setup
 After cloning create a copy of sample configuration file:
 
 ```sh
$ cp ./example/includes/_credentials.sample.php ./example/includes/_credentials.php
 ```

 Update `_credentials.php` with own credentials:
 
 ```php
<?php

return [
    'username' => 'my_username',
    'password' => 'my_pasword',
    'roleType' => 'M', // account type, M = merchant, A = affiliate/publisher
];
```

 We assume you already have some PHP installed. If not, just run:
 
 ```sh
sudo apt install php7.3-common php7.3-curl php7.3-cli -y
``` 

## Run an example script
 Now just execute one of two example files. Let's say we're searching transactions:
 
 ```sh
php ./example/pap-search_transaction.php
``` 

 or we want to approve some transaction's status:
 
 ```sh
php ./example/pap-transaction_approve.php
``` 
 
 Please refer to either file to see comments.
 