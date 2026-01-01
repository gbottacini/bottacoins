<?php
include "db.php";

$usuario = "atendente";
$senha = "123456";

$hash = md5($senha);

$stmt = $conn->prepare("UPDATE usuarios SET senha=? WHERE usuario=?");
$stmt->bind_param("ss", $hash, $usuario);
$stmt->execute();

echo "Usuário criado com sucesso!";
