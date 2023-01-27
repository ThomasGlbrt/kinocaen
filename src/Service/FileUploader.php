<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function FileUploader(UploadedFile $file, $originalFileName = null)
    {
        if($originalFileName) {
            if(file_exists($this->getTargetDirectory().'/'.$originalFileName)) {
                return $originalFileName;
            } else {
                try {
                    $file->move($this->getTargetDirectory(), $originalFileName);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                return $originalFileName;
            }
        } else {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = $originalFilename.'-'.uniqid().'.'.$file->guessExtension();
            try {
                $file->move($this->getTargetDirectory(), $fileName);
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            return $fileName;
        }
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
