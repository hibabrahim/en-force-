<?php
namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

class StringToFileTransformer implements DataTransformerInterface
{
    public function transform($value): ?string
    {
        // Transform the File object into a string (path)
        return $value;
    }

    public function reverseTransform($value): ?File
    {
        // Transform the string (path) into a File object
        return new File($value);
    }

}
