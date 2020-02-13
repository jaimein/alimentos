<meta charset="UTF-8">
<?php

function obtenerTiempo($poblacion,$pais='es')
{
    //de momento si no se indica otro pais saldra españa
    $html = file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=".$poblacion.",".$pais."&appid=96edde9f7c64ae00b99322b16b678542");
    $json = json_decode($html);
    $info['ciudad'] = $json->name;
    $info['lat'] = $json->coord->lat;
    $info['lon'] = $json->coord->lon;
    $info['temp'] = $json->main->temp;
    $info['tempmax'] = $json->main->temp_max;
    $info['tempmin'] = $json->main->temp_min;
    $info['presion'] = $json->main->pressure;
    $info['humedad'] = $json->main->humidity;
    $info['vel_viento'] = $json->main->temp;
    $info['estado_cielo'] = $json->weather[0]->main;
    $info['descripcion'] = $json->weather[0]->description;

    return $info;
}
?>