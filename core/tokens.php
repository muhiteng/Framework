<?php

$payload = [
    "sub" => $user["id"],
    "name" => $user["firstname"],
    "exp" => time() + 20
];

$access_token = $code->encode($payload);

$refresh_token = $code->encode([
    "sub" => $user["id"],
    "exp" => time() + 432000
]);

echo json_encode([
    "access_token" => $access_token,
    "refresh_token" => $refresh_token
]);