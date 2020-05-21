<?php 

require_once 'inc/credentials.php';
require __DIR__ . '/twilio-php-master/src/Twilio/autoload.php';
use Twilio\Rest\Client;

	$con = mysqli_connect("localhost", "itzelavila", "iaac23", "alertas_covid_nb");
	if(!$con) {
		$msg = "Could not connect to the database. <br/>";
		$msg .= "Error Number:". mysqli_connect_errno();
		$msg .= "Error:". mysqli_connect_error();
		die($msg);
	}

echo "You are connected to your database!<br/>";
echo mysqli_get_host_info($con);
echo "<br/>";


	//pedir datos a alertas_covid_nb 
$query = mysqli_query($con, "SELECT FECHA_TOMA_MUESTRA, APELLIDO_PATERNO, APELLIDO_MATERNO, NOMBRE_S, DISNEA, id, TELEFONO, RESULTADO_DEFINITIVO, RESULTADO_PARCIAL, FOLIO_DE_LA_MUESTRA  FROM base_proof");

$c=1;// iniciamos contador para guardar en arreglo

//$mensajeprueba_negativo ="Esto es una prueba negativo"; //caso negativo
//$mensajeprueba_positivo1="Eso es una prueba positivo1"; //caso positivo grave
//$mensajeprueba_positivo2="Eso es una prueba positivo2"; //caso positivo no grave
$caso_p=0;
$caso_n=0;
$caso_pg=0;
$caso_na=0;
$caso_ne=0;
$folios=array();

//iniciamos escaneo de mensajes y los guardamos en un arreglo
while($patient_info = mysqli_fetch_array($query)){
include 'alertas_reducidas.php';
$tel = "+52".$patient_info['TELEFONO'];

//$telprueba = "+526674967586";	
	# code...

		// CUANDO EL PACIENTE ES NEGATIVO..
		if ($patient_info['RESULTADO_DEFINITIVO']=="NEGATIVO")
        {

			$mensaje=$mensaje_negativo;
			$caso_n++;

			//echo $tel."<br/>";
			
		 // CUANDO EL PACIENTE ES POSITIVO
        }
        elseif($patient_info['RESULTADO_DEFINITIVO']=="NO ADECUADO")
        {
            $mensajes_noenviados[$c]=array($patient_info);
            $caso_ne++;
            continue;
		 }
        elseif($patient_info['RESULTADO_DEFINITIVO']=="SARS-CoV-2" && $patient_info['RESULTADO_PARCIAL']=="SARS-CoV-2"){

		 // EL PACIENTE ES POSITIVO GRAVE
			 	if ($patient_info['DISNEA']=="SI") 
			 	{

			 		$mensaje=$mensaje_positivo_grave;
			 		$caso_pg++;
			 		
			 		//echo $tel."<br/>";
			 	}
			 	// EL PACIENTE ES POSITIVO NO GRAVE
			 		else{
			 			//echo $tel."<br/>";
			 			$mensaje=$mensaje_positivo_nograve;
			 			$caso_p++;

						}
		}
		else{
			
            continue;
				
			}



	// checar números
	
	/*$twilio = new Client($account_sid, $auth_token);

	$phone_number = $twilio->lookups->v1->phoneNumbers($tel)
	                                    ->fetch(["type" => ["carrier"]]);
	//echo $tel."<br/>";

	//echo $phone_number->carrier->type;

	$tipo_linea=$phone_number->carrier['type'];

	if ($tipo_linea=='landline')
		{

		$telefonos_casa[$c]=array($tel,$patient_info['FOLIO_DE_LA_MUESTRA']);
	}*/

	//Creando el arreglo de mensajes y telefonos
    //Si el mensaje es nulo no agregarlo
  

        	$mensajes_listos[$c] = array($mensaje);
        	$telefonos[$c] = array($tel);
        	$c++;
    	
       
     //echo $mensajes_listos[$c]. " con telefono: ". $telefonos[$c];    
    
}

mysqli_close($con); 

//echo '<pre>'; print_r($mensajes_listos); echo '</pre>';
//echo '<pre>'; print_r($telefonos); echo '</pre>';
$l=sizeof($telefonos); // guardar el tamaño del arreglo
$lm=sizeof($mensajes_listos);
echo "casos positivos no graves: ".$caso_p."<br/>";
echo "casos negativos: ".$caso_n."<br/>";
echo "casos positivo grave: ".$caso_pg."<br/>";

echo '<pre>'; print_r($mensajes_listos); echo '</pre>';
echo '<pre>'; print_r($telefonos); echo '</pre>';
echo '<pre>'; print_r($mensajes_noenviados); echo '</pre>';
		
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]
// A Twilio number you own with SMS capabilities
/*

for ($i=1; $i <= $l ; $i++) { 
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



$fileHandler = fopen("mensajes_18may2020.txt","a");
fwrite($fileHandler,"


"."-------------------------------------------------------------------------------------"."        
".$mensajes_listos[$i][0]. " El msj se envió a ". $telefonos[$i][0]);
fclose($fileHandler);


}

$fileHandler = fopen("mensajes_18may2020.txt","a");
fwrite($fileHandler,"


"."-------------------------------------------------------------------------------------"."        
"."casos positivos no graves: ".$caso_p."
".
"casos negativos: ".$caso_n."
".
"casos positivo grave: ".$caso_pg);
fclose($fileHandler);

//echo '<pre>';echo "Telefonos casa: "; print_r($telefonos_casa); echo '</pre>';*/



?>
