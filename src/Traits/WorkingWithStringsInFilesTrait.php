<?php

namespace SchwingeGmbH\SchwingeTallViews\Traits;

trait WorkingWithStringsInFilesTrait
{
    public function insertInFile($search, $insert, $path, $after = true): ?bool
    {
        $errorMsg = "Couldn't add '".trim($insert)."' to '$path'. Please add it manually.";

        // check if the file not exists
        if (! file_exists($path)) {
            return $this->components->warn($errorMsg);
        }
        // check if the file already has the $string
        if (str_contains($fileContentsAsString = file_get_contents($path), $insert)) {
            return true;
        }
        // check if the $keyword we want to add $string after it not in the file
        if (! str_contains($fileContentsAsString, $search)) {
            return $this->components->warn($errorMsg);
        }

        if ($after) {
            file_put_contents($path, substr_replace($fileContentsAsString, PHP_EOL.$insert, strpos($fileContentsAsString, $search) + strlen($search), 0));
        } else {
            file_put_contents($path, substr_replace($fileContentsAsString, $insert.PHP_EOL, strpos($fileContentsAsString, $search), 0));
        }

        return str_contains(file_get_contents($path), $insert) ?? $this->components->warn($errorMsg);
    }

    public function replaceInFile($search, $replace, $path): ?bool
    {
        $errorMsg = "Couldn't replace \"".trim($search).'" with "'.trim($replace)."\" in $path Please replace it manually.";

        // check if the file not exists
        if (! file_exists($path)) {
            return $this->components->warn($errorMsg);
        }
        // check if the file already has the string $replace
        if (str_contains($fileContentsAsString = file_get_contents($path), $replace)) {
            return true;
        }
        // check if the string $search we want to replace it with $replace not in the file
        if (! str_contains($fileContentsAsString, $search)) {
            return $this->components->warn($errorMsg);
        }

        file_put_contents($path, str_replace($search, $replace, $fileContentsAsString));

        return str_contains(file_get_contents($path), $replace) ?? $this->components->warn($errorMsg);
    }
}
