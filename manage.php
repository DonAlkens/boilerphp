<?php

if($argc > 0) {
    $cmd = $argv;

    foreach($cmd as $key => $value){
        echo $key.":".$value."\n";
    }
}
