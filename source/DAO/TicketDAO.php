<?php

namespace Source\DAO;

use Exception;
use PDO;
use PDOException;

use Source\Connect;

class TicketDAO
{
    private $connect;

    public function __construct()
    {
        $this->connect = Connect::getInstance();
    }

    public function beginTransaction()
    {
        return $this->connect->beginTransaction();
    }

    public function commit()
    {
        return $this->connect->commit();
    }

    public function getFila()
    {
        try {
            $sql = "SELECT 
            * 
            FROM ticket
            WHERE status = 'FINALIZADO'
            ORDER BY codigo DESC
            LIMIT 3 OFFSET 0";

            $stmt = $this->connect->prepare($sql);
            /* $stmt->debugDumpParams(); */

            $stmt->execute();
            return ["data" => $stmt->fetchAll()];
        } catch (PDOException $e) {
            throw new Exception("[ERRO][Anotação DAO 01]" . $e->getMessage());
        }
    }

    public function getSeqSenha()
    {
        try {
            $sql = "SELECT 
            * 
            FROM ticket
            WHERE status = 'PENDENTE'
            ORDER BY codigo ASC";

            $stmt = $this->connect->prepare($sql);
            /* $stmt->debugDumpParams(); */

            $stmt->execute();
            return ["data" => $stmt->fetch()];
        } catch (PDOException $e) {
            throw new Exception("[ERRO][Anotação DAO 02]" . $e->getMessage());
        }
    }

    public function senhaChamada()
    {
        try {
            $sql = "SELECT 
            * 
            FROM ticket
            WHERE status = 'CHAMADA'";

            $stmt = $this->connect->prepare($sql);
            /* $stmt->debugDumpParams(); */

            $stmt->execute();
            return ["data" => $stmt->fetch()];
        } catch (PDOException $e) {
            throw new Exception("[ERRO][Anotação DAO 02]" . $e->getMessage());
        }
    }

    public function proxSenha(int $nextCodigo)
    {
        try {
            $sql = "SELECT 
            *
            FROM ticket
            WHERE codigo = :codigo";

            $stmt = $this->connect->prepare($sql);
            /* $stmt->debugDumpParams(); */
            $stmt->bindValue(":codigo", $nextCodigo, PDO::PARAM_INT);

            $stmt->execute();
            return ["data" => $stmt->fetch()];
        } catch (PDOException $e) {
            throw new Exception("[ERRO][Anotação DAO 02]" . $e->getMessage());
        }
    }

    public function getSenhaByNome(string $representante)
    {
        try {
            $sql = "SELECT 
            codigo
            FROM ticket
            WHERE 
            representante LIKE :representante AND status IN ('PENDENTE', 'CHAMADA')";

            $stmt = $this->connect->prepare($sql);

            $stmt->bindValue(":representante", "%$representante%", PDO::PARAM_STR);

            // $stmt->debugDumpParams();

            $stmt->execute();
            return ["data" => $stmt->fetch()];
        } catch (PDOException $e) {
            throw new Exception("[ERRO][Anotação DAO 03]" . $e->getMessage());
        }
    }

    public function gerarTicket(string $representante)
    {
        try {
            $sql = "INSERT INTO ticket(representante, status)
            VALUES (:representante, :status)";

            $stmt = $this->connect->prepare($sql);

            $stmt->bindValue(":representante", $representante, PDO::PARAM_STR);
            $stmt->bindValue(":status", 'PENDENTE', PDO::PARAM_STR);


            /* $stmt->debugDumpParams(); */

            $stmt->execute();

            return ["data" => $this->connect->lastInsertId()];
        } catch (PDOException $e) {
            throw new Exception("[ERRO][Anotação DAO 04]" . $e->getMessage());
        }
    }

    public function editarTicket(int $codigo, string $status)
    {
        try {
            $sql = "UPDATE ticket SET status = :status WHERE codigo = :codigo";

            $stmt = $this->connect->prepare($sql);

            $stmt->bindValue(":status", $status, PDO::PARAM_STR);
            $stmt->bindValue(":codigo", $codigo, PDO::PARAM_INT);


            /* $stmt->debugDumpParams(); */

            $stmt->execute();

            return ["message" => "Ticket editado com sucesso!"];
        } catch (PDOException $e) {
            throw new Exception("[ERRO][Anotação DAO 05]" . $e->getMessage());
        }
    }
}
