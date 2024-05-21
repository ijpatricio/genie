<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class AppPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:package {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Package the app for distribution';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $app_name = $this->argument('name');

        try {
            $this->compressDirectory(
                base_path("sandbox/$app_name"),
                storage_path("app/public/$app_name.zip"),
            );

            $this->info("App packaged at storage/app/public/$app_name.zip");
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
        }
    }

    public function compressDirectory($directory, $zipFilePath)
    {
        // Initialize the Zip Archive
        $zip = new ZipArchive;

        // Create and open the zip file for writing
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {

            // Add files to the zip file
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directory),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                // Skip directories (they would be added automatically)
                if (!$file->isDir()) {
                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($directory) + 1);

                    // Add current file to archive
                    $zip->addFile($filePath, $relativePath);
                }
            }

            // Close the zip file
            $zip->close();

            return true;
        } else {
            return false;
        }
    }
}
