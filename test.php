<?php

include 'FileTransfer/file_transfer_lib.php';

use FileTransfer\Factory;

$factory = new Factory();

try
{
    $conn = $factory->getConnection('ssh', 'kate', '', '', 22);
    var_dump($conn->pwd());
    $conn->download('/home/kate/1.txt', '/home/dev/1.txt');
    $conn->upload('/home/dev/111.txt');
    $conn->close();

}catch(Exception $e){

    echo $e->getMessage();

}


try
{
    $conn = $factory->getConnection('ftp', '', '', '', 21);
    $conn->upload('/home/dev/111.txt');
    $conn->download('111.txt', '/home/dev/222.txt');
    $conn->cd('/zyro');
    $conn->upload('/home/dev/111.txt');
    $conn->close();

}catch(Exception $e){

    echo $e->getMessage();

}