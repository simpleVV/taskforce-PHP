<?php

namespace taskforce\utils;

abstract class AbstractFileSearcher
{
    public array $files = [];

    /**
     * Returns an array with the names of the found files (the full path to the file)
     *  
     * @return ?array - array with the names of the found files or null
     */
    public function getFiles(): ?array
    {
        return $this->files;
    }

    /**
     * File Search method
     *  
     * @return void 
     */
    abstract public function findFiles(): void;
}
