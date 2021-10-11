<?php

class Contas extends Conexao
{
    public function setLogin($agencia, $conta, $senha)
    {
        $pdo = parent::get_instance();
        $sql = "SELECT 
                    * 
                FROM contas 
                WHERE agencia = :agencia AND conta = :conta AND senha = :senha";
        $sql = $pdo->prepare($sql);
        $sql->bindValue(":agencia", $agencia);
        $sql->bindValue(":conta", $conta);
        $sql->bindValue(":senha", $senha);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $sql = $sql->fetch();

            $_SESSION['login'] = $sql['id'];

            header("Location: ../index.php?login_sucesso");
            exit;
        }else {
            header("Location: ../login.php?login_falha");
        }
    }

    public function listarContas()
    {
        $pdo = parent::get_instance();
        $sql = "SELECT 
                    * FROM contas";
        $sql = $pdo->prepare($sql);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            return $sql->fetchAll();
        }

        return false;
    }

    public function getHistorico($id)
    {
        $pdo = parent::get_instance();
        $sql = "SELECT 
                    * 
                FROM historico 
                WHERE id_conta = :id_conta";
        $sql = $pdo->prepare($sql);
        $sql->bindValue(":id_conta", $id);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            return $sql->fetchAll();
        }
    }

    public function getInformacoesConta($id)
    {
        $pdo = parent::get_instance();
        $sql = "SELECT 
                    * FROM contas
                WHERE id = :id";
        $sql = $pdo->prepare($sql);
        $sql->bindValue(":id", $id);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            return $sql->fetchAll();
        }
    }

    public function adcionarTransacao($tipo, $valor)
    {

        $pdo = parent::get_instance();
        $sql = "INSERT INTO historico (id_conta, tipo, valor, data_operacao) 
                               VALUES (:id_conta, :tipo, :valor, NOW())";
        $sql = $pdo->prepare($sql);
        $sql->bindValue(":id_conta", $_SESSION['login']);
        $sql->bindValue(":tipo", $tipo);
        $sql->bindValue(":valor", $valor);
        $sql->execute();

        if ($tipo == "Deposito") {       
            $sql = "UPDATE contas SET saldo = saldo + :valor WHERE id = :id";
            $sql = $pdo->prepare($sql);
            $sql->bindValue(":valor", $valor);
            $sql->bindValue(":id", $_SESSION['login']);
            $sql->execute();
        }else {    
            $sql = "UPDATE contas SET saldo = saldo - :valor WHERE id = :id";
            $sql = $pdo->prepare($sql);
            $sql->bindValue(":valor", $valor);
            $sql->bindValue(":id", $_SESSION['login']);
            $sql->execute();
        }
    }

    public function logout() {
        unset($_SESSION['login']);
    }
}