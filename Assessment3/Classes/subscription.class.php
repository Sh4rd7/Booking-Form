<?php

class Subscription
{
    static protected $database;

    static public function set_database($database)
    {
        self::$database=$database;
    }


static public function find_by_sql($sql){
    $result = self::$database->query($sql);
    if(!$result){
        exit("Database query failed");
    }

    $sub_obj = [];
    while ($record = $result->fetch_assoc()){
        $sub_obj[] = self::newSubscription($record);
    }

    $result->free();

    return $sub_obj;
}


static protected function newSubscription($record){
    $subscription = new self;
    foreach ($record as $property => $value){
        if (property_exists($subscription, $property)){
            $subscription->$property=$value;
        }
    }
    return $subscription;
}


static public function find_by_sub_id($subID){
    $sql = "SELECT * FROM subscriptions ";
    $sql .= "WHERE subID='".self::$database->escape_string($subID)."'";
    $sub_obj = self::find_by_sql($sql);
    if (!empty($sub_obj)){
        return array_shift($sub_obj);
    }
    else{
        return false;
    }
    }


static public function find_all(){
    $sql = "SELECT * FROM subscriptions ORDER BY subID DESC";
    return self::find_by_sql($sql);
}



public function create(){
    $sql = "INSERT INTO subscriptions (";
    $sql .="subUsername, subNickname, fullName, email, password";
    $sql .=") VALUES (";
    $sql .="'".$this->subUsername . "',";
    $sql .="'".$this->subNickname . "',";
    $sql .="'".$this->fullName . "',";
    $sql .="'".$this->email . "',";
    $sql .="'".$this->password . "'";
    $sql .=")";

    echo "<br />sql " . $sql;

    $result = self::$database->query($sql);
    if ($result){
        $this->subID = self::$database->insert_id;
    } else{
        echo "<br />sql " . $sql;
    }
    return $result;
}



public static function update($args = []){
    $sql = "UPDATE subscriptions SET subNickname = ";
    $sql .="'" . $args['subNickname']."', fullName =";
    $sql .="'" . $args['fullName']."', email =";
    $sql .="'" . $args['email']."' WHERE subID =";
    $sql .="'" . $args['subID']."'";

    echo "<br />sql ".$sql;

    $result = self::$database->query($sql);
    return $result;
}


public static function delete($subID){
    $sql = "DELETE FROM subscriptions WHERE subID = ";
    $sql .= "'" . $subID . "'";
    echo "<br />sql" . $sql;

    $result = self::$database->query($sql);
    return $result;
}


public $subID;
public $subUsername;
public $subNickname;
public $fullName;
public $email;
public $password;


public function __construct($args =[])
{
    $this->subUsername = $args['subUsername']??'';
    $this->subNickname = $args['subNickname']??'';
    $this->fullName = $args['fullName']??'';
    $this->email = $args['email']??'';
    $this->password = $args['password']??'';
}
}


