<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Resumenter\FileLocator;

class FileLocatorTest extends TestCase
{
    private string $testDir;
    private FileLocator $fileLocator;

    protected function setUp(): void
    {
        $this->fileLocator = new FileLocator();

        // Tworzymy tymczasowy katalog testowy
        $this->testDir = sys_get_temp_dir() . '/filelocator_test_' . uniqid();
        mkdir($this->testDir);

        // Tworzymy przykładowe pliki i foldery
        file_put_contents($this->testDir . '/file1.json', '{}');
        file_put_contents($this->testDir . '/file2.txt', 'abc');
        file_put_contents($this->testDir . '/.hidden', 'x');
        mkdir($this->testDir . '/subdir');
    }

    protected function tearDown(): void
    {
        // Usuwamy pliki i foldery testowe
        array_map('unlink', glob($this->testDir . '/*'));
        @rmdir($this->testDir . '/subdir');
        @rmdir($this->testDir);
    }

    public function testGetFileListReturnsOnlyFilesWithExtensions(): void
    {
        $result = $this->fileLocator->getFileList($this->testDir);

        $this->assertContains('file1.json', $result);
        $this->assertContains('file2.txt', $result);
        $this->assertContains('.hidden', $result); // ma nazwę, ale niekoniecznie rozszerzenie
        $this->assertNotContains('subdir', $result);
    }

    public function testGetFileListReturnsEmptyArrayForInvalidPath(): void
    {
        $result = $this->fileLocator->getFileList('/invalid/path/to/folder');
        $this->assertSame([], $result);
    }

    public function testGetFileNamesReturnsFilenamesWithoutExtensions(): void
    {
        $result = $this->fileLocator->getFileNames($this->testDir);

        $this->assertContains('file1', $result);
        $this->assertContains('file2', $result);
        $this->assertNotContains('subdir', $result);
    }

    public function testGetFileNamesReturnsEmptyArrayForEmptyFolder(): void
    {
        $emptyDir = $this->testDir . '/empty';
        mkdir($emptyDir);
        $result = $this->fileLocator->getFileNames($emptyDir);

        $this->assertSame([], $result);

        @rmdir($emptyDir);
    }
}
