<?php 

$string = file_get_contents("data.json");
echo '{
		"cols":[
			{"type":"string"},
			{"type":"number"},
			{"type":"number"}
		],
		"rows":[';
echo $string;
echo '] }';

?>
