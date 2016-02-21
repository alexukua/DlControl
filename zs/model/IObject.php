<?php


namespace Zs\Model;


class IObject
{


    public function __construct($id)
    {
        $this->setId($id);
    }

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

    public function setTitle($value, $lang)
    {

        $t = new \stdClass();
        $this->title[] = array('title' => $t->$value, 'lang' => $t->$lang);

        return $this->title;
    }


    public function getTitle($value, $lang)
    {

        return $this->title;
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