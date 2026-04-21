<?php

class BLLSmartphone 
{
    //-------CLASS FIELDS------------------
    public $id = null;
    public $make;
    public $model;
    public $screen_size;
    public $dimensions;
    public $weight;
    public $release;
    public $os;
    public $price;
    public $score;
    public $desc;
    public $desc_href;
    
    public function fromArray(stdClass $passoc)
    {
        foreach($passoc as $tkey => $tvalue)
        {
            $this->{$tkey} = $tvalue;
        }
    }
}

class BLLCategory{
    private $id;
    private $userId;
    private $name;

    public function setId($id){
        $this->id = $id;
    }
    public function setUserId($userId){
        $this->userId = $userId;
    }
    public function setName($name){
        $this->name = $name;
    }

    public function getId(){
        return $this->id;
    }
    public function getUserId(){
        return $this->userId;
    }
    public function getName(){
        return $this->name;
    }
}

class BLLTransaction{
    private $id;
    private $userId;
    private $name;
    private $date;
    private $amount;
    private $type;
    private $catergoryName;
    private $currency;
    private $notes;

    public function setId($id){
        $this->id = $id;
    }
    public function setUserId($userId){
        $this->userId = $userId;
    }
    public function setName($name){
        $this->name = $name;
    }
    public function setDate($date){
        $this->date = $date;
    }
    public function setAmount($amount){
        $this->amount = $amount;
    }
    public function setType($type){
        $this->type = $type;
    }
    public function setCategoryName($catergoryName){
        $this->catergoryName = $catergoryName;
    }
    public function setCurrency($currency){
        $this->currency = $currency;
    }
    public function setNotes($notes){
        $this->notes = $notes;
    }

    public function getId(){
        return $this->id;
    }
    public function getUserId(){
        return $this->userId;
    }
    public function getName(){
        return $this->name;
    }
    public function getDate(){
        return $this->date;
    }
    public function getAmount(){
        return $this->amount;
    }
    public function getType(){
        return $this->type;
    }
    public function getCategoryName(){
        return $this->catergoryName;
    }
    public function getCurrency(){
        return $this->currency;
    }
    public function getNotes(){
        return $this->notes;
    }
}

class BLLUser implements jsonSerializable
{
    private $id;
    private $email;
    private $fname;
    private $lname;
    private $password;
    private $defaultCurrency;

    public function setId($id){
        $this->id = $id;
    }
    public function setEmail($email){
        $this->email = $email;
    }
    public function setFname($fname){
        $this->fname = $fname;
    }
    public function setLname($lname){
        $this->lname = $lname;
    }
    public function setPassword($password){
        $this->password = $password;
    }
    public function setDefaultCurrency($defaultCurrency){
        $this->defaultCurrency = $defaultCurrency;
    }

    public function getId(){
        return $this->id;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getFname(){
        return $this->fname;
    }
    public function getLname(){
        return $this->lname;
    }

    public function getPassword(){
        return $this->password;
    }
    public function getDefaultCurrency(){
        return $this->defaultCurrency;
    }

    public function jsonSerialize(): mixed {
        return [
            'id'       => $this->id,
            'email'    => $this->email,
            'fname'    => $this->fname,
            'lname'    => $this->lname,
            'password' => $this->password,
            'defaultCurrency' => $this->defaultCurrency
        ];
    }
}
?>