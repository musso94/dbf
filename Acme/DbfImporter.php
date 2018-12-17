<?php
/**
 * Created by PhpStorm.
 * User: Musso
 * Date: 07.11.18
 * Time: 12:15
 */

namespace App\Acme\Dbf;


use Exception;

class DbfImporter
{
    private $type;
    private $path;

    /**
     * DbfImporter constructor.
     * @param $type
     * @param $path
     */
    public function __construct($type, $path)
    {
        $this->type = $type;
        $this->path = $path;
    }

    /**
     * @return string Type of import
     */
    protected function getType()
    {
        return $this->type;
    }

    /**
     * @param array $fields
     * @return array
     * @throws Exception
     */
    public function getAssocData(array $fields)
    {
        try {
            $this->checkDbaseFunctions();
        } catch (\Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        try {
            $db = dbase_open($this->path, 0);
        } catch (\Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        $recordNumbers = dbase_numrecords($db);

        $assocData = [];

        for ($i = 1; $i <= $recordNumbers; $i++) {
            $row = dbase_get_record_with_names($db, $i);
            $a = [];

            for ($j = 0; $j < count($fields); $j++)
                $a[$fields[$j]] = $row[$fields[$j]];

            $assocData[] = $a;
        }

        dbase_close($db);

        return $assocData;
    }

    /**
     * @throws Exception
     */
    private function checkDbaseFunctions()
    {
        if (!function_exists('dbase_open')) {
            throw new Exception('DfbImporter: dbase_open() function does not exist!');
        }

        if (!function_exists('dbase_numrecords')) {
            throw new Exception('DfbImporter: dbase_numrecords() function does not exist!');
        }

        if (!function_exists('dbase_get_record_with_names')) {
            throw new Exception('DfbImporter: dbase_get_record_with_names() function does not exist!');
        }

        if (!function_exists('dbase_close')) {
            throw new Exception('DfbImporter: dbase_close() function does not exist!');
        }

    }
}