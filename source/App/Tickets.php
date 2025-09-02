<?php

namespace Source\App;

use Source\Models\Ticket;

class Tickets
{
    function getFila()
    {
        try {
            $ticket = new Ticket();
            echo json_encode($ticket->getFila());
        } catch (\Throwable $th) {
            jsonError($th->getMessage(), $th->getCode());
        }
    }

    function carregarSenhaChamada()
    {
        try {
            $ticket = new Ticket();
            echo json_encode($ticket->senhaChamada());
        } catch (\Throwable $th) {
            jsonError($th->getMessage(), $th->getCode());
        }
    }

    function gerarTicket($param)
    {
        try {
            $ticket = new Ticket();
            $ticket->setRepresentante($param["representante"]);
            echo json_encode($ticket->gerarTicket());
        } catch (\Throwable $th) {
            jsonError($th->getMessage(), $th->getCode());
        }
    }

    function getSenhaByNome($param)
    {
        try {
            $ticket = new Ticket();
            $ticket->setRepresentante($param["representante"]);
            echo json_encode($ticket->getSenhaByNome());
        } catch (\Throwable $th) {
            jsonError($th->getMessage(), $th->getCode());
        }
    }

    function proxSenha($param)
    {
        try {
            $ticket = new Ticket();

            if (!empty($param["senhaAtual"])) $ticket->setCodigo((int) $param["senhaAtual"]);

            echo json_encode($ticket->proxSenha());
        } catch (\Throwable $th) {
            jsonError($th->getMessage(), $th->getCode());
        }
    }
}
