<?php

define("URL_BASE", "http://localhost:8080/ticket");

define("SITE", "GoTicket");

define("CONNECT_CONFIG", [
    "driver" => "mysql",
    "host" => "localhost",
    "port" => "3306",
    "dbname" => "ticket",
    "username" => "root",
    "passwd" => "",
    "options" => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);


/**
 * @param string $path
 * @return string
 */

function url(string $path): string
{
    if ($path) {
        return URL_BASE . "{$path}";
    }
    return URL_BASE;
}

function jsonError($mensagem, $codigo = 500)
{
    http_response_code($codigo);
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'erro',
        'mensagem' => $mensagem
    ]);
    exit;
}

function convertNull($value)
{
    return empty($value) ? null : $value;
}
