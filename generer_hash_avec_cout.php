<?php
$motDePasse = 'abracadabra';

$optionsBcrypt = [
    'cost' => 12,
];
$hash = password_hash($motDePasse, PASSWORD_BCRYPT, $optionsBcrypt);

echo "mot de passe = $motDePasse <br>";
echo "hash correspondant à cet instant : <br>";
echo $hash;
