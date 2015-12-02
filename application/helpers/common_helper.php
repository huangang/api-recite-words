<?php
/**
 * Created by PhpStorm.
 * User: huangang
 * Date: 15/9/1
 * Time: 下午6:40
 */

function dump($var){

    echo '<pre>';

    print_r($var);
}


function https_request($url){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}
    curl_close($curl);
    return $data;
}