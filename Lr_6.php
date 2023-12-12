<?php

$curl = curl_init("http://www.gismeteo.ua/city/hourly/5053/");

curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Повертати відповідь у вигляді рядка
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);  // Слідкувати за перенаправленнями, якщо такі є
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Ігнорувати перевірку SSL-сертифіката 
$out = curl_exec($curl);


if (curl_errno($curl)) {
    curl_close($curl);
}
curl_close($curl);

echo '<link rel="stylesheet" href="Lr_6.css">';

$patternCity = '/METEOFOR: (.*?),/us';
if (preg_match($patternCity, $out, $matches)) {
    $city = $matches[1];
    echo "<html><header>{$city}<br>";
} else {
    echo 'Не вдалося знайти місто';
    echo '<br>';
}

$data = '/cacheDate: \'(\d{2}\.\d{2}\.\d{4}) \d{2}:\d{2}:\d{2} \(UTC\)\'/us';
if (preg_match($data, $out, $matches)) {
    $dData = $matches[1];
    echo "{$dData}</header>";
    echo '<br>';
} else {
    echo 'Не вдалося знайти дату.';
    echo '<br>';
}

$patternSunrise = '/Схід — (\d+:?\d+)/us';
if (preg_match($patternSunrise, $out, $matches)) {
    $sunriseTime = $matches[1];
    echo '<div class="base">Схід Сонця: ' . $sunriseTime;
}

echo '<br>';


$patternSunset = '/Захід — (\d+:\d+)/us';
if (preg_match($patternSunset, $out, $matches)) {
    $sunsetTime = $matches[1];
    echo "Захід Сонця: " . $sunsetTime;
    echo '<br>';
} else {
    echo 'Не вдалося знайти час заходу сонця.';
    echo '<br>';
}



$duration = '/Тривалість дня: (\d+) год (\d+) хв/us';
if (preg_match($duration, $out, $matches)) {
    $hours = $matches[1];
    $minutes = $matches[2];
    if($hours==1||$hours==21)
    {
        if($minutes==1||$minutes==21||$minutes==31||$minutes==41||$minutes==51){
            echo "Тривалість дня: $hours година $minutes хвилина";   
        }
        else if($minutes==2||$minutes==3||$minutes==4||$minutes==22||$minutes==23||$minutes==24||$minutes==32||$minutes==33||$minutes==34||
        $minutes==42||$minutes==43||$minutes==44||$minutes==52||$minutes==53||$minutes==54)
        {
            echo "Тривалість дня: $hours година $minutes хвилини"; 
        }
        else if($minutes==0)
        {
            echo "Тривалість дня: рівно $hours година";
        }
        else{ echo "Тривалість дня: $hours година $minutes хвилин";   }
       
    }
    else if ($hours==2||$hours==3||$hours==4||$hours==22||$hours==23||$hours==24)
    {
        if($minutes==1||$minutes==21||$minutes==31||$minutes==41||$minutes==51){
            echo "Тривалість дня: $hours години $minutes хвилина";   
        }
        else if($minutes==2||$minutes==3||$minutes==4||$minutes==22||$minutes==23||$minutes==24||$minutes==32||$minutes==33||$minutes==34||
        $minutes==42||$minutes==43||$minutes==44||$minutes==52||$minutes==53||$minutes==54)
        {
            echo "Тривалість дня: $hours години $minutes хвилини"; 
        }
        else if($minutes==0)
        {
            echo "Тривалість дня: рівно $hours години";
        }
        else {echo "Тривалість дня: $hours години $minutes хвилин";}
          
    }
    else 
    {  
        if($minutes==1||$minutes==21||$minutes==31||$minutes==41||$minutes==51){
            echo "Тривалість дня: $hours годин $minutes хвилина";   
        }
        else if($minutes==2||$minutes==3||$minutes==4||$minutes==22||$minutes==23||$minutes==24||$minutes==32||$minutes==33||$minutes==34||
        $minutes==42||$minutes==43||$minutes==44||$minutes==52||$minutes==53||$minutes==54)
        {
            echo "Тривалість дня: $hours годин $minutes хвилини"; 
        }
        else if($minutes==0)
        {
            echo "Тривалість дня: рівно $hours годин";
        }
        else {echo "Тривалість дня: $hours годин $minutes хвилин";}
          
    }
    
    echo '<br>';
} else {
    echo 'Не вдалося знайти тривалість дня.';
    echo '<br>';
}
echo "—————————————————————";
echo"<br>";
$hourPattern = '/<span>\s*(\d{1,2})<sup class="time-sup">\d{2}<\/sup>\s*<\/span>/';
$temperatureP = '/<span class="unit unit_temperature_c">(.*?)<\/span>/';

if (preg_match_all($hourPattern, $out, $hourMatches) && preg_match_all($temperatureP, $out, $temperatureMatches)) {
    $hours = $hourMatches[1];
    $temperatures = $temperatureMatches[1];

    for ($i = 0; $i < min(8, count($hours)); $i++) {
        echo "$hours[$i]г: {$temperatures[$i+8]} C&deg";
        echo "<br>";
    }
    echo "</div></html>";
} else {
    echo 'Не вдалося знайти години або температури.';
}
