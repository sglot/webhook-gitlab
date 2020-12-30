<?php
/* error_reporting(E_ALL); ini_set('display_errors',1);*/

const PATH = "/home/bitrix/www/webhook-gitlab/";
const TOKEN = "INSERT_GITLAB_TOKEN_MAYBE_FROM_ENV"; // токен из гитлаба
const TRUE_BRANCH = "master";
const TRUE_STATUS = "can_be_merged";

$validation = false;

$result = "";
$output = array();
$response = [
    "error" => "no auth data",
    "success" => 0
];


$headers = apache_request_headers();
$body = @file_get_contents("php://input");
$body = json_decode($body, true);

if (!empty($headers)
    && isset($headers['X-Gitlab-Token'],
        $headers['X-Gitlab-Event'],
        $body['object_attributes']['target_branch'],
        $body['object_attributes']['merge_status']
    )
    && $headers['X-Gitlab-Token'] == TOKEN
    && $headers['X-Gitlab-Event'] == "Merge Request Hook"
    && $body['object_attributes']['target_branch'] === TRUE_BRANCH
    && $body['object_attributes']['merge_status'] === TRUE_STATUS
) {
    $response = [
        "error" => "",
        "success" => 0
    ];

    $validation = true;
}

ob_end_clean();
ignore_user_abort();
ob_start();
header("Connection: close");
header('Content-type:application/json;charset=utf-8');
echo json_encode($response);
header("Content-Length: " . ob_get_length());
ob_end_flush();
flush();

if ($validation === true) {
    $date = new DateTime("now", new DateTimeZone('Europe/Moscow') );
    $result = exec('/bin/sh ' . PATH . 'bin/gitpull.sh ' . $_SERVER['SERVER_NAME'] . '  2>&1', $output);
}

die();