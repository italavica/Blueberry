<?php  
$now= time();
$fecha_hora= date("Y-m-d H:i:s", $now -6*3600);

$mensaje_positivo_grave = "Notificación de resultado COVID- 19, Servicios de Salud de Sinaloa. ".$fecha_hora."                           
".$patient_info['NOMBRE_S']." ".$patient_info['APELLIDO_PATERNO']." ".$patient_info['APELLIDO_MATERNO']."                                                 "."    ".
"Su resultado es POSITIVO a CORONAVIRUS(SARS-COV-2), su prueba fue tomada el día:".$patient_info['FECHA_TOMA_MUESTRA']."              

"."Monitorea tus síntomas: dificultad para respirar, dolor torácico, tos, mal estado general, alteración de la conciencia, etc. En caso de no estar hospitalizado, será necesario acudir al hospital de derechohabiencia más cercano para tu valoración inmediata (IMSS, ISSSTE, SEDENA, SEMAR), o bien si usted no tiene derechohabiencia podrá acudir a los hospitales de los Servicios de Salud de Sinaloa.

*Si requiere de una ambulancia para su traslado llame inmediatamente al teléfono 6677130063 y mencione que su resultado ha sido positivo a SARS-COV-2.";
//----------------------------------------------------------------------
$mensaje_positivo_nograve = "Notificación de resultado COVID- 19, Servicios de Salud de Sinaloa. ".$fecha_hora."                           
".$patient_info['NOMBRE_S']." ".$patient_info['APELLIDO_PATERNO']." ".$patient_info['APELLIDO_MATERNO']."                                                 "."    ".
"Su resultado es POSITIVO a CORONAVIRUS(SARS-COV-2), su prueba fue tomada el día:".$patient_info['FECHA_TOMA_MUESTRA']."            

"."Con este resultado es de suma importancia QUEDARSE EN CASA, además de seguir al pie de la letra las siguientes recomendaciones, visita la página: https://coronavirus.gob.mx/ o el teléfono 6677130063 del CALL CENTER de los Servicios de Salud de Sinaloa.

En caso de que sus síntomas empeoren (falta de aire o dificultad para respirar), será necesario acudir al hospital de derechohabiencia más cercano (IMSS, ISSSTE, SEDENA, SEMAR), o bien si usted no tiene derechohabiencia podrá acudir a los hospitales de los Servicios de Salud de Sinaloa.
";
//----------------------------------------------------------------------
$mensaje_negativo= "Notificación de resultado COVID- 19, Servicios de Salud de Sinaloa. ".$fecha_hora."                           
".$patient_info['NOMBRE_S']." ".$patient_info['APELLIDO_PATERNO']." ".$patient_info['APELLIDO_MATERNO']."                                                 "."    ".
"Su resultado es NEGATIVO A CORONAVIRUS (SARS-COV-2).
 Su prueba fue tomada el día: ".$patient_info['FECHA_TOMA_MUESTRA']."              
Aún con este resultado es necesario seguir al pie de la letra las siguientes recomendaciones, visita la página: https://coronavirus.gob.mx/ o el teléfono 6677130063 del CALL CENTER de los Servicios de Salud de Sinaloa.";


?>