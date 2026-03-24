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

class BLLUser implements jsonSerializable
{
    private $email;
    private $fname;
    private $lname;
    private $username;
    private $password;

    public function setEmail($email){
        $this->email = $email;
    }
    public function setFname($fname){
        $this->fname = $fname;
    }
    public function setLname($lname){
        $this->lname = $lname;
    }
    public function setUsername($username){
        $this->username = $username;
    }
    public function setPassword($password){
        $this->password = $password;
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
    public function getUsername(){
        return $this->username;
    }
    public function getPassword(){
        return $this->password;
    }

    public function jsonSerialize(): mixed {
        return [
            'email'    => $this->email,
            'fname'    => $this->fname,
            'lname'    => $this->lname,
            'username' => $this->username,
            'password' => $this->password,
        ];
    }
}
?>