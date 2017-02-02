<?php
namespace FileTransfer;

use Exception;

class SshClient implements ClientInterface
{

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
        $this->connection_id = ssh2_connect($host, $port);

        if (!$this->connection_id) {
            throw new Exception("Не удалось установить соединение с $host:$port");
        }

        if (!ssh2_auth_password($this->connection_id, $login, $pass)) {
            throw new Exception("Не удалось авторизоваться");
        }
    }


    /**
     * @inheritdoc
     */
    public function cd(string $remote_path) {
        $this->exec('cd ' . $remote_path);
    }

    /**
     * @inheritdoc
     */
    public function download(string $remote_file_path, string $local_file_path) {
        if (!@ssh2_scp_recv($this->connection_id, $remote_file_path, $local_file_path)) {
            throw new Exception("Не удалось скачать файл " . $remote_file_path);
        }
    }

    /**
     * @inheritdoc
     */
    public function upload(string $local_file_path) {
        $remote_file_path = pathinfo($local_file_path);
        $file_name = $remote_file_path['basename'];
        $current_dir = trim($this->pwd($this->connection_id));
        if (!ssh2_scp_send($this->connection_id, $local_file_path, $current_dir . '/' . $file_name, 0644)) {
            throw new Exception("Не удалось загрузить файл " . $local_file_path);
        }
    }

    /**
     * @inheritdoc
     */
    public function pwd() : string {
        return $this->exec('pwd');
    }

    /**
     * @inheritdoc
     */
    public function exec($command) {
        $stream = ssh2_exec($this->connection_id, $command);
        $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
        stream_set_blocking($errorStream, true);
        stream_set_blocking($stream, true);
        $content =  stream_get_contents($stream);
        $error = stream_get_contents($errorStream);

        if ($error) {
            throw new Exception($error);
        }

        fclose($stream);
        fclose($errorStream);
        return $content;
    }

    /**
     * @inheritdoc
     */
    public function close() {
        $this->connection = null;
    }

}