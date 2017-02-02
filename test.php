<?php

include 'FileTransfer/file_transfer_lib.php';

use FileTransfer\Factory;

$factory = new Factory();

try
{
    $conn = $factory->getConnection('ssh', 'kate', 'sE1RGn1w', '54.149.133.230', 22);
    var_dump($conn->pwd());
    $conn->download('/home/kate/1.txt', '/home/dev/1.txt');
    $conn->upload('/home/dev/111.txt');
    $conn->close();

}catch(Exception $e){

    echo $e->getMessage();

}