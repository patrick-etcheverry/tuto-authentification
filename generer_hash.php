<?php
$motDePasse = 'abracadabra';
$hash = password_hash($motDePasse, PASSWORD_BCRYPT);

echo "mot de passe = $motDePasse <br>";
echo "hash correspondant à cet instant : <br>";
echo $hash;
