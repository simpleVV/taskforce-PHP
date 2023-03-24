<?php

namespace taskforce\utils;

use FilesystemIterator;
use taskforce\utils\AbstractFileSearcher;
use taskforce\utils\exception\SourceFileException;

class ExtensionFileSearcher extends AbstractFileSearcher
{
    private string $fileExtension;
    private string $dir;

    /**
     * @param string $dir - директория для поиска файлов
     * @param string $fileExtension - расширение искомых файлов 
     * @return void
     */
    public function __construct(string $dir, string $fileExtension)
    {
        if (!is_dir($dir)) {
            throw new SourceFileException("Директория не найден");
        }

        $this->dir = $dir;
        $this->fileExtension = $fileExtension;
    }

    /**
     * Метод выполняет поиск файлов по расширению и сохраняет их в массив
     * @return void 
     */
    public function findFiles(): void
    {
        $fileIterator = new FilesystemIterator($this->dir);

        foreach (new FilesystemIterator($this->dir) as $file) {
            if ($fileIterator->getExtension() === $this->fileExtension) {
                $this->files[] = $file->getFileInfo();
            }
        }
    }
}
