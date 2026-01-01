<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("POST não recebido");
}

/* RECEBENDO DADOS */
$tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : "";
$nick = isset($_POST["nick"]) ? $_POST["nick"] : "";
$whatsapp = isset($_POST["whatsapp"]) ? $_POST["whatsapp"] : "";
$coins = isset($_POST["coins"]) ? intval($_POST["coins"]) : 0;
$valor = 0;

if (isset($_POST["valor"])) {
    $valor = str_replace(",", ".", $_POST["valor"]);
    $valor = floatval($valor);
}


$comprovante = "";

/* VALIDAÇÃO */
if ($tipo == "" || $nick == "" || $coins <= 0) {
    die("Dados inválidos");
}

/* UPLOAD DO PRINT */
if (isset($_FILES["print"]) && $_FILES["print"]["name"] != "") {

    $uploadDir = $_SERVER["DOCUMENT_ROOT"] . "/uploads/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $ext = pathinfo($_FILES["print"]["name"], PATHINFO_EXTENSION);
    $nome = uniqid("print_") . "." . $ext;

    if (!move_uploaded_file($_FILES["print"]["tmp_name"], $uploadDir . $nome)) {
        die("Erro ao salvar imagem");
    }

    $comprovante = $nome;
}

/* INSERT NO BANCO (SEM PREPARED, compatível PHP antigo) */
$sql = "
INSERT INTO pedidos 
(tipo, nick, whatsapp, coins, valor, comprovante, status)
VALUES (
    '".mysqli_real_escape_string($conn, $tipo)."',
    '".mysqli_real_escape_string($conn, $nick)."',
    '".mysqli_real_escape_string($conn, $whatsapp)."',
    ".$coins.",
    ".$valor.",
    '".mysqli_real_escape_string($conn, $comprovante)."',
    'Pendente'
)";

if (!mysqli_query($conn, $sql)) {
    die("Erro SQL: " . mysqli_error($conn));
}

echo "PEDIDO SALVO COM SUCESSO | VALOR: R$ ".$valor;
