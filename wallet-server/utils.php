<?php

function return_success()
{
    $response = [];
    $response["success"] = true;
    return json_encode($response);
}

function return_failure($string)
{
    $response = [];
    $response["failure"] = $string;
    return json_encode($response);
}
