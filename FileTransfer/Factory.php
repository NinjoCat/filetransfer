<?php
namespace FileTransfer;

class Factory {

    /**
     * @param string $type
     * @param string $login
     * @param string $pass
     * @param string $host
     * @param string $port
     * @return FtpClient|SshClient
     * @throws \Exception
     */
    public function getConnection(string $type, string $login, string $pass, string $host, string $port) {
        if (!in_array($type, ['ssh', 'ftp'])) {
            throw new \Exception('Неправильный тип соединения');
        }
        switch ($type) {
            case 'ssh' :
                return new SshClient($login, $pass, $host, $port);
            case 'ftp' :
                return new FtpClient($login, $pass, $host, $port);
        }
    }
}