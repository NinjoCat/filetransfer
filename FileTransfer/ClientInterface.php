<?php
namespace FileTransfer;

use Exception;

/**
 *  Интерфейс взалимодействия с удаленныем сервером
 */
interface ClientInterface {

    /**
     * Изменить текущую директорию
     *
     * @param string $path
     *
     * @throws Exception
     */
    public function cd(string $path);

    /**
     * Скачивает файл с сервера
     *
     * @param string $remote_file_path
     * @param string $local_file_path
     *
     * @return
     */
    public function download(string $remote_file_path, string $local_file_path);

    /**
     * Загружает файл на сервер
     *
     * @param string $local_file_path
     *
     * @throws Exception
     */
    public function upload(string $local_file_path);

    /**
     * Возвращает имя текущей директории
     *
     * @return string
     *
     * @throws Exception
     */
    public function pwd() : string ;

    /**
     * Выполняет команду на сервере
     *
     * @return array
     *
     * @throws Exception
     */
    public function exec($commnad);


    /**
     * Закрывает соединение с сервером
     *
     * @throws Exception
     */
    public function close();
}