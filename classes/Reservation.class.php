<?php

class Reservation {

    private $_db;

    public function __construct() {
        $this->_db = new PDO('sqlite:' . __DIR__ . '/../db/db.sqlite3');
        $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }


    public function getTimes() {

        $ex = $this->_db->prepare('SELECT rowid as id, time_from, time_to FROM time');
        $ex->execute();
        return  $ex->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getReserved($date) {

        $ex = $this->_db->prepare('SELECT time_id FROM reservations where date=:date');
        $ex->bindValue(':date', date('Y-m-d', strtotime($date)));
        $ex->execute();
        return  $ex->fetchAll(PDO::FETCH_COLUMN);
    }

    public function addReservation($date, $timeId) {

        $alreadyReserved = $this->getReserved($date);


        if(in_array($timeId, $alreadyReserved) || !$this->checkAvailableTimeIds($timeId)) return false;
        echo $date;

        $ex = $this->_db->prepare('INSERT INTO reservations values (:date, :time_id)');
        $ex->bindValue(':date', $date);
        $ex->bindValue(':time_id', $timeId);
        $ex->execute();

        return  true;

    }

    private function checkAvailableTimeIds($timeId) {
        $ex = $this->_db->prepare('SELECT rowid FROM time');
        $ex->execute();
        $res = $ex->fetchAll(PDO::FETCH_COLUMN);
        return in_array($timeId, $res);

    }

}