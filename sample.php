<?php
header('Content-Type: application/json');
require 'NIK-Translator.php';
$NIK = new NIKTranslator;
print json_encode($NIK->parse('Masukkan NIK disini..'), JSON_PRETTY_PRINT);
