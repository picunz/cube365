<?php
// File di test per Xdebug

echo "Debug Test Started<br>";

$nome = "Giovanni";
$eta = 30;
$dati = [
    'città' => 'Roma',
    'paese' => 'Italia'
];

echo "Nome: $nome<br>";
echo "Età: $eta<br>";
echo "Dati: " . print_r($dati, true) . "<br>";

// METTI UN BREAKPOINT QUI SOTTO (riga 17)
$risultato = $nome . " ha " . $eta . " anni";

echo "Risultato: $risultato<br>";
echo "Debug Test Completed!";
?>
