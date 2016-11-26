<?php
namespace BlogBundle\Services;
use Symfony\Component\HttpFoundation\File\UploadedFile;
class ImgUploader
{
    private $targetDir;
    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }
    public function upload(UploadedFile $file)
    {
        $fileName = $file->guessExtension();
        $file->move($this->targetDir, $fileName);
        return $fileName;
    }
}