<?php
$conn = mysqli_connect("localhost", "root", "030998", "bottacoins");

if (!$conn) {
    die("Erro ao conectar ao banco");
}
