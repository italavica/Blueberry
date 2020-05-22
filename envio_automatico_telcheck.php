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
$a=1;

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



if (!$patient_info['TELEFONO']==NULL && strlen($patient_info['TELEFONO'])==10){

	$tel = "+52".$patient_info['TELEFONO'];

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
            $mensajes_noenviados[$a]=array($patient_info['NOMBRE_S'],$patient_info['APELLIDO_PATERNO'],$patient_info['APELLIDO_MATERNO'],$patient_info['RESULTADO_DEFINITIVO'],$patient_info['FOLIO_DE_LA_MUESTRA']);
            $a++;
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

    
  

        	$mensajes[$c] = array($mensaje);
        	$telefonos[$c] = array($tel);
        	$c++;

        }else{

        	$mensajes_noenviados[$a]=array($patient_info['NOMBRE_S'],$patient_info['APELLIDO_PATERNO'],$patient_info['APELLIDO_MATERNO'],$patient_info['RESULTADO_DEFINITIVO'],$patient_info['FOLIO_DE_LA_MUESTRA']);
        	$a++;
        	$caso_ne++;
        }
    	
       
     //echo $mensajes_listos[$c]. " con telefono: ". $telefonos[$c];    
        
    
}

mysqli_close($con); 

echo '<pre>'; echo "Mensajes: ";print_r($mensajes); echo '</pre>';
echo '<pre>'; echo "Telefonos: ";print_r($telefonos); echo '</pre>';
echo '<pre>'; echo "mensajes_noenviados: ";print_r($mensajes_noenviados); echo '</pre>';

echo "casos positivos no graves: ".$caso_p."<br/>";
echo "casos negativos: ".$caso_n."<br/>";
echo "casos positivo grave: ".$caso_pg."<br/>";


$li=sizeof($telefonos);
$b=1;
$c=1;
$d=1;
	for ($i=1; $i <= $li ; $i++)
	{

	

			$telefono =implode(" ",$telefonos[$i]);

			
			$twilio = new Client($account_sid, $auth_token);

			$phone_number = $twilio->lookups->v1->phoneNumbers($telefono)
			                                    ->fetch(["type" => ["carrier"]]);

			print_r($phone_number->carrier);
			//echo $phone_number->carrier->type;

			$tipo_linea=$phone_number->carrier['type'];
			$e=$phone_number->carrier['error_code'];

			if ($tipo_linea=='landline'){

				$telefonos_casa[$b]=array($telefonos[$i],$mensajes[$i]);
				$b++;

			} elseif ($e=='60600'){  

				$numero_novalido[$c]=array($telefonos[$i],$mensajes[$i]);
				$c++;
				$caso_ne++;

			}else{

				$telefonos_listos[$d]=array($telefonos[$i]);
				$mensajes_listos[$d]=array($mensajes[$i]);
				$d++;
			}

		}
	
	

	
	



echo '<pre>'; echo "Mensajes listos: ";print_r($mensajes_listos); echo '</pre>';
echo '<pre>'; echo "Telefonos listos: ";print_r($telefonos_listos); echo '</pre>';
echo '<pre>'; echo "Telefonos casa: ";print_r($telefonos_casa); echo '</pre>';
echo '<pre>'; echo "Mensajes sin enviar: ";print_r($numero_novalido); echo '</pre>'; 




		
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]
// A Twilio number you own with SMS capabilities
$li=sizeof($telefonos_listos); // guardar el tamaño del arreglo
$lm=sizeof($mensajes_listos);

for ($i=1; $i <= $li ; $i++) { 
	echo '<pre>'; print_r($mensajes_listos[$i][0][0]); echo '</pre>';
	echo '<pre>'; print_r($telefonos_listos[$i][0][0]); echo '</pre>';

$celular =implode(" ",$telefonos_listos[$i][0]);
$msg=implode(" ",$mensajes_listos[$i][0]);

$client = new Client($account_sid, $auth_token);
$client->messages->create(
    // Where to send a text message (your cell phone?)
    $celular,
    array(
        'from' => $twilio_number,
        'body' => $msg
    )
);



$fileHandler = fopen("mensajes_22may2020.txt","a");
fwrite($fileHandler,"


"."-------------------------------------------------------------------------------------"."        
".$mensajes_listos[$i][0][0]. " El msj se envió a ". $telefonos_listos[$i][0][0]);
fclose($fileHandler);


}



//------------MENSAJES NO ENVIADOS GUARDAR----------------------
$fileHandler = fopen("mensajes_22may2020.txt","a");
fwrite($fileHandler,"


"."-------------------------------------------------------------------------------------"."        
"."Mensajes sin enviar:  ");

fclose($fileHandler);
$lmne=sizeof($mensajes_noenviados);

for ($i=1; $i <= $lmne ; $i++){ 

$fileHandler = fopen("mensajes_22may2020.txt","a");
fwrite($fileHandler,"
"."-------------------------------------------------------------------------------------"."        
".$mensajes_noenviados[$i][0].$mensajes_noenviados[$i][1].$mensajes_noenviados[$i][2]."
".
"Resultado: ".$mensajes_noenviados[$i][3]."
".
"Folio muestra: ".$mensajes_noenviados[$i][4]);

fclose($fileHandler);
}


//------------TELEFONOS DE CASA---------------------------------
$fileHandler = fopen("mensajes_22may2020.txt","a");
fwrite($fileHandler,"


"."-------------------------------------------------------------------------------------"."        
"."Pacientes con telefono de casa: ");

fclose($fileHandler);

$ltc=sizeof($telefonos_casa);
for ($i=1; $i <= $ltc ; $i++) { 

$fileHandler = fopen("mensajes_22may2020.txt","a");
fwrite($fileHandler,"
"."-------------------------------------------------------------------------------------"."        
"."Telefono: ".$telefonos_casa[$i][0][0]."
".
"Mensaje: ".$telefonos_casa[$i][1][0]);

fclose($fileHandler);

}

//------------NUMEROS NO VALIDOS---------------------------------
$fileHandler = fopen("mensajes_22may2020.txt","a");
fwrite($fileHandler,"


"."-------------------------------------------------------------------------------------"."        
"."Pacientes con número no válido:  ");

fclose($fileHandler);

$lnv=sizeof($numero_novalido);
for ($i=1; $i <= $lnv ; $i++) { 

$fileHandler = fopen("mensajes_22may2020.txt","a");
fwrite($fileHandler,"
"."-------------------------------------------------------------------------------------"."        
"."Telefono: ".$numero_novalido[$i][0][0]."
".
"Mensaje: ".$numero_novalido[$i][1][0]);

fclose($fileHandler);

}

//------------CONTEO DE CASOS------------------------------------
$fileHandler = fopen("mensajes_22may2020.txt","a");
fwrite($fileHandler,"


"."-------------------------------------------------------------------------------------"."        
"."casos positivos no graves: ".$caso_p."
".
"casos negativos: ".$caso_n."
".
"casos positivo grave: ".$caso_pg."
".
"mensajes sin enviar: ".$caso_ne);

fclose($fileHandler);


?>
