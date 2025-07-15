<?php

namespace MenyongMenying\MLibrary\Kucil\Utilities\MJson;

use MenyongMenying\MLibrary\Kucil\Utilities\MArray\MArray;
use MenyongMenying\MLibrary\Kucil\Utilities\MData\MData;
use MenyongMenying\MLibrary\Kucil\Utilities\MObject\MObject;

/**
 * @author MenyongMenying <menyongmenying.main@email.com>
 * @version 0.0.1
 * @date 2025-07-10
 */
final class MJson
{
    /**
     * Objek dari class 'MArray'.
     * @var \MenyongMenying\MLibrary\Kucil\Utilities\MArray\MArray 
     */
    private MArray $mArray;

    /**
     * Objek dari class 'MObject'.
     * @var \MenyongMenying\MLibrary\Kucil\Utilities\MObject\MObject 
     */
    private MObject $mObject;

    /**
     * @param  \MenyongMenying\MLibrary\Kucil\Utilities\MArray\MArray $mArray
     * @param  \MenyongMenying\MLibrary\Kucil\Utilities\MData\MData   $mData  
     * @param  \MenyongMenying\MLibrary\Kucil\Utilities\MObject\MObject $mObject 
     * @return void
     */
    public function __construct(MArray $mArray, MObject $mObject)
    {
        $this->mArray = $mArray;
        $this->mObject = $mObject;
        return;
    }

    /**
     * Mengecek apakah suatu string merupakan JSON.
     * @param  string $json String yang akan dicek.
     * @return bool         Hasil pengecekan.
     */
    public function isJson(string $json) :bool
    {
        $this->decode($json);
        return $this->getLastError() === JSON_ERROR_NONE;
    }

    /**
     * Melakukan encode data ke dalam JSON.
     * @param  array       $data   Array yang akan diencode.
     * @param  int         $option 
     * @param  int         $depth  
     * @return null|string         
     */
    public function encode(array $data, int $option = 0, int $depth = 512) :null|string
    {
        $result = json_encode($data, $option, $depth);
        switch (true) {
            case $this->mArray->isEmpty($data):
                return '{}';
            case !$this->mArray->isArrayAssociative($data):
                return throw new \Exception('Data yang diberikan bukan array asosiatif.');
            case JSON_ERROR_NONE === $this->getLastError():
                return $result;
            default:
                return null;
        }
        return null;
    }

    /**
     * Melakukan decode string JSON.
     * @param  string $json       String yang akan didecode.
     * @param  bool   $assosiatif Melakukan konvert hasil decode ke array atau tidak.   
     * @param  int    $dept       
     * @param  int    $flags      
     * @return mixed              
     */
    public function decode(string $json, bool $assosiatif = false, int $dept = 512, int $flags = 0) :mixed
    {
        $result = json_decode($json, false, $dept, $flags);
        if ($this->getLastError() === JSON_ERROR_NONE) {
            if ($assosiatif) {
                return $this->mObject->convertToArray($result);
            }
            return new MData($result);
        }
        return null;
    }

    /**
     * Meneruskan kode error terakhir dari proses JSON.
     * @return int 
     */
    public function getLastError() :int
    {
        return json_last_error();
    }

    /**
     * Meneruskan error terakhir dari proses JSON.
     * @return string 
     */
    public function getLastErrorMessage() :string
    {
        return json_last_error_msg();
    }

    /**
     * Mengecek apakah decode JSON menghasilakn error.
     * @return bool 
     */
    public function hasError() :bool
    {
        if (json_last_error() === JSON_ERROR_NONE) {
            return false;
        }
        return true;
    }
}