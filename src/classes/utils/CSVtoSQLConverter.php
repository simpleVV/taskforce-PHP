<?php

namespace taskforce\utils;

use RuntimeException;
use SplFileInfo;
use SplFileObject;
use taskforce\utils\exception\SourceFileException;
use taskforce\utils\exception\FileFormatException;

class CSVtoSQLConverter
{
    private SplFileInfo $file;
    protected SplFileObject $fileObject;

    /**
     * @param SplFileInfo $file - bнформация о файле
     * @return void
     */
    public function __construct(SplFileInfo $file)
    {
        if (!file_exists($file->getPathname())) {
            throw new SourceFileException("Файл не найден");
        }

        $this->file = $file;
    }

    /**
     * Создает файл sql с именем переданного файла, в той же директории где
     * лежит исходный файл. Преобразует данные в csv файле в sql запрос.
     * @param string $outputDirectory - директория для сохранения файла 
     * @return void
     */
    public function importCSVToSQL($outputDirectory): void
    {
        try {
            $this->fileObject = new SplFileObject($this->file);
        } catch (RuntimeException) {
            throw new SourceFileException(("Файл не доступен для чтения"));
        }

        $this->fileObject->setFlags(SplFileObject::READ_CSV);
        $columns = $this->getColumnsNames();

        if (!$this->validateColumns($columns)) {
            throw new FileFormatException("Заголовки столбцов должны быть указаны как строчные значения");
        }

        $filesToConvert = [];

        while (!$this->fileObject->eof()) {
            $filesToConvert[] = $this->fileObject->fgetcsv();
        }

        $tableName = $this->fileObject->getBasename(".csv");
        $query = $this->createSQLQuery($tableName, $columns, $filesToConvert);

        $this->createSQLFile($outputDirectory, $tableName, $query);
    }

    /**
     * Создает insert - msql запрос из переданных данных (имя таблицы, параметры, значения).
     * @param string $tableName - имя таблицы msql
     * @param array $columns - имя колонок таблицы msql 
     * @param array $values - значения таблицы msql  
     * @return string
     */
    protected function createSQLQuery(string $tableName, array $columns, array $values): string
    {
        $formatColumns = array_map(function (string $column) {
            return trim($column, " ");
        }, $columns);

        $queryColumns = implode(", ", $formatColumns);
        $queryValues = "";

        foreach ($values as $value) {
            $value =
                $queryValues .= "(" . "'" . implode("', '", [...$value]) . "'" . "),\n";
        }

        $queryValues = rtrim($queryValues, ",\n");
        $query = "INSERT INTO `$tableName` ($queryColumns)
        VALUES $queryValues;";

        return $query;
    }

    /**
     * Создает и заполняет msql файл с заданным именем, в указанной директории
     * @param string $dir - каталог куда будет помещен файл
     * @param string $fileName - имя файла msql 
     * @param string $content - содержимое msql файла  
     * @return void
     */
    protected function createSQLFile(string $dir, string $fileName, string $content): void
    {
        if (!is_dir($dir)) {
            throw new SourceFileException("Не найдена директория для сохранения файлов");
        }

        $newFileName = $dir . DIRECTORY_SEPARATOR . $fileName . '.sql';
        file_put_contents($newFileName, $content);
    }

    /**
     * Сохраняет название колонок csv файла в массив
     * @return ?array - массив названий колонок csv файла или null если данные
     * в файле отсутствуют
     */
    protected function getColumnsNames(): ?array
    {
        $this->fileObject->rewind();
        $data = $this->fileObject->fgetcsv();

        return $data;
    }

    /**
     * Проверяет заголовки столбцов на соответствие строковому формату.
     * @param array $columns - массив заголовков
     * @return bool - true - если заголовки есть в файле и соответствуют
     * формату, иначе false
     */
    protected function validateColumns($columns): bool
    {
        $isValid = true;

        if (count($columns)) {
            foreach ($columns as $column) {
                if (!is_string($column))
                    $isValid = false;
            }
        } else {
            $isValid = false;
        }

        return $isValid;
    }
}
