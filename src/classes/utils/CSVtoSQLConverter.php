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
     * @param SplFileInfo $file - file info
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
     * Creates an sql file with the name of the transferred file, 
     * in the same directory where the source file is located. 
     * Converts data in a csv file into an sql query.
     * 
     * @param string $outputDirectory - the directory for saving the file 
     * @return void
     */
    public function importCSVToSQL($outputDirectory): void
    {
        try {
            $this->fileObject = new SplFileObject($this->file);
        } catch (RuntimeException) {
            throw new SourceFileException(('Файл не доступен для чтения'));
        }

        $this->fileObject->setFlags(SplFileObject::READ_CSV);
        $columns = $this->getColumnsNames();

        if (!$this->validateColumns($columns)) {
            throw new FileFormatException('Заголовки столбцов должны быть указаны как строчные значения');
        }

        $filesToConvert = [];

        while (!$this->fileObject->eof()) {
            $filesToConvert[] = $this->fileObject->fgetcsv();
        }

        $tableName = $this->fileObject->getBasename('.csv');
        $query = $this->createSQLQuery($tableName, $columns, $filesToConvert);

        $this->createSQLFile($outputDirectory, $tableName, $query);
    }

    /**
     * Creates an insert - msql query from the transmitted data (table name, parameters, values)
     * 
     * @param string $tableName - table name msql
     * @param array $columns - columns names msql 
     * @param array $values - table values msql  
     * @return string
     */
    private function createSQLQuery(string $tableName, array $columns, array $values): string
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
     * Creates and fills in an msql file with the specified name, 
     * in the specified directory
     * 
     * @param string $dir - the directory where the file will be placed
     * @param string $fileName - file name msql 
     * @param string $content - content msql file  
     * @return void
     */
    private function createSQLFile(string $dir, string $fileName, string $content): void
    {
        if (!is_dir($dir)) {
            throw new SourceFileException('Не найдена директория для сохранения файлов');
        }

        $newFileName = $dir . DIRECTORY_SEPARATOR . $fileName . '.sql';
        file_put_contents($newFileName, $content);
    }

    /**
     * Saves the column names of the csv file to an array
     * 
     * @return ?array - array of column names of the csv file or null if the data
     * the file is missing
     */
    private function getColumnsNames(): ?array
    {
        $this->fileObject->rewind();
        $data = $this->fileObject->fgetcsv();

        return $data;
    }

    /**
     * Checks column headers for compliance with string format.
     * 
     * @param array $columns - array of title
     * @return bool - true - if the headers are in the file and correspond to
     * format, otherwise false
     */
    private function validateColumns($columns): bool
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
