<?php

namespace taskforce\utils;

abstract class AbstractFileSearcher
{
    public array $files = [];

    /**
     * Возвращает массив с именами найденных файлов (полный путь до файла) 
     * @return ?array - массив с именами найденных файлов или null если не чего не найдено
     */
    public function getFiles(): ?array
    {
        return $this->files;
    }

    /**
     * Метод по поиску файлов 
     * @return void 
     */
    abstract public function findFiles(): void;
}
