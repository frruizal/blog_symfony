<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    public function upload(UploadedFile $file)
    {
        $safeFilename = $slugger->slug($originalFilename); 
        $newFilename = $safeFilename.'-'.uniqid().'.'.$imagenSubida->guessExtension(); 
        $path = "uploads/".date('n');

        $pathToImage = $path."/".$newFilename;
        try {
            $imagenSubida->move($path, $newFilename); 
        } catch (FileException $e) {  }
    }
}
