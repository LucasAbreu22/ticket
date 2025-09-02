<?php

use CoffeeCode\Router\Router;

require __DIR__ . "/vendor/autoload.php";

$router = new Router(URL_BASE);

$router->namespace("Source\App");

/* 
    EXEMPLO ROTA

    $router->group(null);
    $router->get("/", "Form:home", "form.home"); 
    $router->get("/{FILTER}", "Form:filter", "form.contato"); 

*/

/* ROTA RAIZ */
$router->group(null);
$router->get("/", "Web:home");
$router->post("/", "Tickets:proxSenha");

$router->group("fila");
$router->get("/", "Web:fila");
$router->post("/getFila", "Tickets:getFila");
$router->post("/carregarSenhaChamada", "Tickets:carregarSenhaChamada");
$router->post("/gerarTicket", "Tickets:gerarTicket");
$router->post("/getSenhaByNome", "Tickets:getSenhaByNome");

/* ROTA DE ERRO */
$router->group("error");
$router->get("/{errcode}", "Web:error");

$router->dispatch();

if ($router->error()) {
    $router->redirect("/error/{$router->error()}");
}
