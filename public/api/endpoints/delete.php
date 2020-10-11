<?php

$_['status'] = false;

if (isset($payload['dbtable'])) {
	$value = $payload['colvalue'];
	if ($payload['stringFlag']) {
		$value = '"' . $value . '"';
	}

	if (set_db_data('DELETE from ' . $payload['dbtable'] . ' where ' . $payload['colname'] . ' = ' . $value)) {
		$_['delStatus'] = true;
	} else {
		$_['delStatus'] = true;
	}
}
