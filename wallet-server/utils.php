<?php

function return_success()
{
    $response = [];
    $response["success"] = true;
    return json_encode($response);
}

function return_failure()
{
    $response = [];
    $response["success"] = false;
    return json_encode($response);
}
