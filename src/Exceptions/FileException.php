<?php

namespace MohammadMehrabani\RepositoryGenerator\Exceptions;

use Exception;

class FileException extends Exception
{
    public static function notWritableDirectory($directory)
    {
        return new static('Not writable directory, check permissions: '.$directory);
    }
}
