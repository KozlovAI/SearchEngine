<?php
return array(
	'source' => 'mysql://root:root@127.0.0.1/sphinxsearch',
	
	'query_pre' => ['SET NAMES utf8'],
	
	'query_data' => 'SELECT id, first_name, last_name, parent_name, org_id',
	
	'query_post' => [],

	'columns' => [
		'id'          => 'integer',
		'first_name'  => 'string',
		'last_name'   => 'string',
		'parent_name' => 'string',
		'org_id'      => 'integer',
	],

	'listen' => array(
		'sphinx' => '127.0.0.1:9312',
		'mysql'  => '127.0.0.1:9306',
	),
);