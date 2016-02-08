<?php


namespace Zs\Model;


class IObject
{

    /**
     * Get ID Information Object
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ID Information Object
     * @param $id
     * @return mixed
     */
    public function setId($id)
    {
        return $this->id = $id;
    }

//    /**
//     *
//     *
//     * @return mixed
//     */
//
//    public function getKey(){
//
//        return $this->key;
//    }
//
//    public function setKey($key){
//        return $this->key=$key;
//    }
}