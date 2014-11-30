<?php

header('Content-Type: application/json');

function api_format_encode($data) {
	return json_encode($data, JSON_UNESCAPED_UNICODE);
}

?>