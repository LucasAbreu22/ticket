<?php

namespace Source\Models;

use Exception;
use Source\DAO\TicketDAO;

class Ticket
{
    private int $codigo;
    private string $representante;

    function __contruct()
    {
        $this->codigo = 0;
    }

    public function setRepresentante(string $param)
    {
        if (empty($param)) throw new Exception("[ERRO 01][TICKET CLSS] Informação de representante vazia!", 501);

        $this->representante = strtoupper($param);
    }

    public function setCodigo(int $param)
    {
        if (empty($param)) throw new Exception("[ERRO 04][TICKET CLSS] Informação de CÓDIGO vazia!", 501);
        if ($param <= 0) throw new Exception("[ERRO 05][TICKET CLSS] Informação de CÓDIGO inválida!", 501);

        $this->codigo = $param;
    }

    public function getFila()
    {
        $ticketDAO = new TicketDAO();
        return $ticketDAO->getFila();
    }

    public function senhaChamada()
    {
        $ticketDAO = new TicketDAO();

        $callback = $ticketDAO->senhaChamada();

        if (!$callback["data"]) {

            $callback = $ticketDAO->getSeqSenha();

            if ($callback["data"]) $ticketDAO->editarTicket($callback["data"]->codigo, 'CHAMADA');

            else return ["data" => false];
        }

        return $callback;
    }

    public function gerarTicket()
    {
        try {
            if (empty($this->representante)) throw new Exception("Informação de representante vazia!", 501);

            $ticketDAO = new TicketDAO();

            $founded = $ticketDAO->getSenhaByNome($this->representante);

            if (!$founded["data"]) $callback = $ticketDAO->gerarTicket($this->representante);

            else $callback = $founded;

            return $callback;
        } catch (\Throwable $th) {
            throw new Exception("[ERRO 02][TICKET CLSS] " . $th->getMessage());
        }
    }

    public function getSenhaByNome()
    {
        try {
            if (empty($this->representante)) throw new Exception("Informação de representante vazia!", 501);

            $ticketDAO = new TicketDAO();
            return $ticketDAO->getSenhaByNome($this->representante);
        } catch (\Throwable $th) {
            throw new Exception("[ERRO 03][TICKET CLSS] " . $th->getMessage());
        }
    }

    public function proxSenha()
    {
        try {
            $ticketDAO = new TicketDAO();

            if (!empty($this->codigo)) $ticketDAO->editarTicket($this->codigo, 'FINALIZADO');

            $nextTicket = $ticketDAO->getSeqSenha();

            if ($nextTicket["data"]) $ticketDAO->editarTicket($nextTicket["data"]->codigo, 'CHAMADA');

            else return ["data" => false];

            return $nextTicket;
        } catch (\Throwable $th) {
            throw new Exception("[ERRO 06][TICKET CLSS] " . $th->getMessage());
        }
    }
}
