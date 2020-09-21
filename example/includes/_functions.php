<?php

/**
 * Performs POST to given URL.
 *
 * @param string $json is encapsulated into 'D' variable
 * @param string $url
 *
 * @return bool|string
 */
function post($json, $url)
{
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['D' => $json]));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	curl_close($ch);

	return $response;
}

/**
 * @param string   $column
 * @param string[] $columns
 *
 * @return false|int|string
 */
function getColumnKey($column, $columns) {
	return array_search($column, $columns);
}
