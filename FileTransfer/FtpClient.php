<?php
namespace FileTransfer;

use Exception;

class FtpClient implements ClientInterface {

    private $connection_id;

    /**
     * @param string $login
     * @param string $pass
     * @param string $host
     * @param int $port
     *
     * @throws Exception
     */
    public function __construct(string $login, string $pass, string $host, int $port) {
        $this->connection_id = ftp_connect($host, $port);
        ftp_login($this->connection_id,$login, $pass);

        if (!$this->connection_id) {
            throw new Exception("Не удалось установить соединение с $host");
        }
    }

    /**
     * @inheritdoc
     */
    public function cd(string $path) {
        if (!ftp_chdir($this->connection_id, $path)) {
            throw new Exception("Не удалось сменить директорию");
        }
    }

    /**
     * @inheritdoc
     */
    public function download(string $remote_file_path, string $local_file_path) {
        if (!ftp_get($this->connection_id, $local_file_path, $remote_file_path, FTP_BINARY)) {
            throw new Exception("Не удалось скачать файл " . $remote_file_path);
        }
    }

    /**
     * @inheritdoc
     */
    public function upload(string $local_file_path) {
        $remote_file_path = pathinfo($local_file_path);
        $file_name = $remote_file_path['basename'];
        $current_dir = ftp_pwd($this->connection_id);

        if (!ftp_put($this->connection_id, $current_dir . '/' . $file_name, $local_file_path, FTP_BINARY)) {
            throw new Exception("Не удалось загрузить файл " . $remote_file_path);
        }
    }

    /**
     * @inheritdoc
     */
    public function pwd() : string {
        $current_dir = ftp_pwd($this->connection_id);
        if (!$current_dir) {
            throw new Exception("Не удалось получить текущую директорию");
        }
        return $current_dir;
    }

    /**
     * @inheritdoc
     */
    public function exec($commnad) {
        $result = ftp_exec($this->connection_id , $commnad);
        if (!$result) {
            throw new Exception("Не удалось выполнить комманду");
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function close() {
        ftp_close($this->connection_id);
    }
}