<?php

if(!empty($_POST['rsv'])) {

include __DIR__ . '/../classes/Reservation.class.php';


    $data = $_POST['date'];
    $resv = new Reservation();

    foreach($_POST['times'] as $time) {
        $resv->addReservation($data, $time);
    }

    return true;



}