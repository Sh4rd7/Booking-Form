<?php

class Booking
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
        $sub_obj[] = self::newBooking($record);
    }

    $result->free();

    return $sub_obj;
}



    static protected function newBooking($record){
        $Booking = new self;
        foreach ($record as $property => $value){
            if (property_exists($Booking, $property)){
                $Booking->$property=$value;
            }
        }
        return $Booking;
    }

static public function find_by_booking_ID($BookingID){
    $sql = "SELECT * FROM booking ";
    $sql .= "WHERE BookingID='".self::$database->escape_string($BookingID)."'";
    $sub_obj = self::find_by_sql($sql);
    if (!empty($sub_obj)){
        return array_shift($sub_obj);
    }
    else{
        return false;
    }
    }

    static public function find_by_booking($booking){
        $sql = "SELECT * FROM booking ";
        $sql .= "WHERE booking='".self::$database->escape_string($booking)."'";
        $booking_obj = self::find_by_sql($sql);
        if (!empty($booking_obj)){
            return array_shift($booking_obj);
        }
        else{
            return false;
        }
    }

static public function find_all(){
    $sql = "SELECT * FROM booking ORDER BY BookingID DESC";
    return self::find_by_sql($sql);
}




    public function create_Booking(){
        $sql = "INSERT INTO booking (";
        $sql .="Name, numberofPeople, alley";
        $sql .=") VALUES (";
        $sql .="'".$this->Name . "',";
        $sql .="'".$this->numberofPeople . "',";
        $sql .="'".$this->alley . "'";
        $sql .=")";

        echo "<br />sql " . $sql;

        $result = self::$database->query($sql);
        if ($result){
            $this->BookingID = self::$database->insert_id;
        } else{
            echo "<br />sql " . $sql;
        }
        return $result;
    }

    public static function update_booking($args = []){
        $sql = "UPDATE booking SET Name = ";
        $sql .="'" . $args['Name']."', numberofPeople =";
        $sql .="'" . $args['numberofPeople']."', alley =";
        $sql .="'" . $args['alley']."' WHERE BookingID =";
        $sql .="'" . $args['bookingID']."'";

        echo "<br />sql ".$sql;

        $result = self::$database->query($sql);
        return $result;
    }




    public static function delete_booking($bookingID){
        $sql = "DELETE FROM booking WHERE BookingID = ";
        $sql .= "'" . $bookingID . "'";
        echo "<br />sql" . $sql;

        $result = self::$database->query($sql);
        return $result;
    }

public $BookingID;
public $Name;
public $numberofPeople;
public $alley;

public function __construct($args =[])
{
    $this->Name = $args['Name']??'';
    $this->numberofPeople = $args['numberofPeople']??'';
    $this->alley = $args['alley']??'';
}
}


