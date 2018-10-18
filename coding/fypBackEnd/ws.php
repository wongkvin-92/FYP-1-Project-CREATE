<?php

require_once('vendor/autoload.php');

$socket = new \HemiFrame\Lib\WebSocket\WebSocket("10.125.194.162", 8080);
//$socket->setEnableLogging(true);

$socket->on("receive", function($client, $data) use($socket) {
    foreach ($socket->getClients() as $client) {
        /* @var $item \HemiFrame\Lib\WebSocket\Client */
	$socket->sendData($client, $data);
    }
});


print ("Starting server..\n");
$socket->startServer();
print ("Done\n");

?>
