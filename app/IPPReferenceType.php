<?php

namespace App;

class IPPReferenceType
{

    private $array;
    private $data = [];

//    public function __construct(array $array)
//    {
//        foreach ($array as $item) {
//            if (isset($item['name']) && isset($item['value'])) {
//                $this->$array[] = [
//                    'name' => $item['name'],
//                    'value' => $item['value'],
//                ];
//            }
//        }
////        $this->array = $array;
//    }
    public function __construct(array $data)
    {
        foreach ($data as $item) {
            if (isset($item['name']) && isset($item['value'])) {
                $this->data[] = [
                    'name' => $item['name'],
                    'value' => $item['value'],
                ];
            }
        }
    }

    public function getElement($index)
    {
        if (array_key_exists($index, $this->array)) {
            return $this->array[$index];
        }

        return null;
    }

    public function setElement($index, $value)
    {
        $this->array[$index] = $value;
    }

    public function getArray()
    {
        return $this->data;
    }

    public function length()
    {
        return count($this->array);
    }
}