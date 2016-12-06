<?php

// our wp-cli commands
$includes = array(
	'class-rest-api-toolbox-base-command.php',
	'class-rest-api-toolbox-rest-api-command.php',
	'class-rest-api-toolbox-ssl-command.php'
	);

foreach ( $includes as $include ) {
	require_once REST_API_TOOLBOX_ROOT . 'includes/wp-cli/' . $include;
}
