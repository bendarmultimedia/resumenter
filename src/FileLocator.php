<?php

namespace Resumenter;

use Throwable;

final class FileLocator
{
    /**
     * @param string $path
     * @return array
     */
    public function getFileList(string $path): array
    {
        $folderInfo = $this->scanDir($path);
        return array_filter($folderInfo, function ($file) use ($path) {
            $filePath = rtrim($path,"/").'/' . $file;
            return is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION);
        });
    }

    /**
     * @param string $path
     * @return array
     */
    private function scanDir(string $path): array
    {
        try {
            $result = scandir($path);
            return is_array($result) ? $result : [];
        } catch (Throwable $exception) {
            return [];
        }
    }

    /**
     * @param string $path
     * @return array
     */
    public function getFileNames(string $path): array
    {
        $files = $this->getFileList($path);
        return array_map(function ($file) {
            return pathinfo($file, PATHINFO_FILENAME);
        }, $files);
    }
}