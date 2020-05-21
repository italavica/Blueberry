<?php
use Twilio\Rest\Client;
//-------------------------------------------------------------------------------------
function enviar_sms($mensajes_listos,$telefonos)
{
require_once 'inc/credentials.php';
require __DIR__ . '/twilio-php-master/src/Twilio/autoload.php';

$l=sizeof($telefonos);// guardar el tamaño del arreglo


	
	for ($i=1; $i <= $l ; $i++) //ciclo for para mandar todos los mensajes del arreglo
	{ 
		echo '<pre>'; print_r($mensajes_listos[$i][0]); echo '</pre>';
		echo '<pre>'; print_r($telefonos[$i][0]); echo '</pre>';

		$client = new Client($account_sid, $auth_token);
		$client->messages->create(
		    // Where to send a text message (your cell phone?)
		    $telefonos[$i][0],
		    array(
		        'from' => $twilio_number,
		        'body' => $mensajes_listos[$i][0]
		    )
		);

		//Guardar mensajes enviados en un archivo de texto
		$fileHandler = fopen("mensajes_enviados.txt","a");
		fwrite($fileHandler,"


		"."-------------------------------------------------------------------------------------"."        
		".$mensajes_listos[$i][0]. " El msj se envió a ". $telefonos[$i][0]);
		fclose($fileHandler);
	}
}

//-----------------------------------------------------------------------



 ?>