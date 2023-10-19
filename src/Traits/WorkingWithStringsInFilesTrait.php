<?php

namespace WisamAlhennawi\LaraFormsBuilder\Traits;

trait WorkingWithStringsInFilesTrait
{
    public function insertInFile($search, $insert, $path, $after = true): bool
    {
        $errorMsg = "Couldn't add '".trim($insert)."' to '$path'. Please add it manually.";

        // check if the file not exists
        if (! file_exists($path)) {
            $this->components->warn($errorMsg);

            return false;
        }
        // check if the file already has the $string
        if (str_contains($fileContentsAsString = file_get_contents($path), $insert)) {
            return true;
        }
        // check if the $keyword we want to add $string after it not in the file
        if (! str_contains($fileContentsAsString, $search)) {
            $this->components->warn($errorMsg);

            return false;
        }

        if ($after) {
            file_put_contents($path, substr_replace($fileContentsAsString, PHP_EOL.$insert, strpos($fileContentsAsString, $search) + strlen($search), 0));
        } else {
            file_put_contents($path, substr_replace($fileContentsAsString, $insert.PHP_EOL, strpos($fileContentsAsString, $search), 0));
        }

        // check if the $insert string inserted or not
        if (str_contains(file_get_contents($path), $insert)) {
            return true;
        } else {
            $this->components->warn($errorMsg);

            return false;
        }
    }

    public function replaceInFile($search, $replace, $path): bool
    {
        $errorMsg = "Couldn't replace \"".trim($search).'" with "'.trim($replace)."\" in $path Please replace it manually.";

        // check if the file not exists
        if (! file_exists($path)) {
            $this->components->warn($errorMsg);

            return false;
        }
        // check if the file already has the string $replace
        if (str_contains($fileContentsAsString = file_get_contents($path), $replace)) {
            return true;
        }
        // check if the string $search we want to replace it with $replace not in the file
        if (! str_contains($fileContentsAsString, $search)) {
            $this->components->warn($errorMsg);

            return false;
        }

        file_put_contents($path, str_replace($search, $replace, $fileContentsAsString));

        // check if the $replace string replaced or not
        if (str_contains(file_get_contents($path), $replace)) {
            return true;
        } else {
            $this->components->warn($errorMsg);

            return false;
        }
    }
}
