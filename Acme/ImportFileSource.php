<?php
/**
 * Created by PhpStorm.
 * User: Musso
 * Date: 08.11.18
 * Time: 11:39
 */

namespace App\Acme\Dbf;


use File;
use Illuminate\Http\UploadedFile;

class ImportFileSource
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * ImportFileSource constructor.
     * @param UploadedFile $file
     */
    public function __construct(UploadedFile $file)
    {
        $fileName =  str_random('32') . $file->getClientOriginalName();
        $file->move(public_path('document/tmp'), $fileName);

        $this->filePath = public_path('document/tmp/' . $fileName);
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * удаляет файл
     */
    public function removeFile()
    {
        File::delete($this->filePath);
    }
}