<?php
class hackers{
  private $handle;
  private $age;
  private $quote;
  private $photo;
  private $like;
  private $dlike;
  private $web;
  private $rev;
  private $guess;
  private $beer;

  function __construct($r){
    $this->handle  = $r['handle'];
    $this->age     = $r['age'];
    $this->quote   = $r['quote'];
    $this->photo   = $r['photo'];
    $this->like    = $r['l'];
    $this->dlike   = $r['d'];
    $this->web     = $r['web'];
    $this->rev     = $r['reverse'];
    $this->guess   = $r['guessing'];
    $this->beer    = $r['beer'];
  }
  function __wakeup(){
    $row=mysql_query("SELECT * from hackers where handle='$this->handle'");
    $r=mysql_fetch_assoc($row);
    $this->__construct($r);
  }
  function get_handle(){
    return $this->handle;
  }
  function get_age(){
    return $this->age;
  }
  function get_quote(){
    return $this->quote;
  }
  function get_photo(){
    return $this->photo;
  }
  function get_like(){
    return $this->like;
  }
  function get_dlike(){
    return $this->dlike;
  }
  function get_web(){
    return $this->web;
  }
  function get_rev(){
    return $this->rev;
  }
  function get_guess(){
    return $this->guess;
  }
  function get_beer(){
    return $this->beer;
  }
}
?>
