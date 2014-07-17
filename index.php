<?php
session_start();
include __DIR__ . '/config/config.php';
include __DIR__ . '/classes/Calendar.class.php';
include __DIR__ . '/classes/Reservation.class.php';

if (!empty($_GET['day']) || !empty($_SESSION['day'])){
    $_SESSION['day']   = (!empty($_GET['day'])) ? $_GET['day'] : $_SESSION['day'];
    $_SESSION['month'] = (!empty($_GET['day'])) ? $_GET['month'] : $_SESSION['month'];
    $_SESSION['year']  = (!empty($_GET['day'])) ? $_GET['year'] : $_SESSION['year'];
    $date = date('Y-m-d', strtotime($_SESSION['year'] . '-' . $_SESSION['month'] . '-' . $_SESSION['day']));

    $reservation = new Reservation();
    $times       = $reservation->getTimes();
    $resv        = $reservation->getReserved($date);
}
    $callendar = new Calendar();
    $callendar->setDayLabels(array("Pn", "Wt", "Śr", "Cz", "Pt", "Sb", "Nd"));
    $callendar->setMonthLabels(array("Sty", "Lut", "Mar", "Kwi", "Maj", "Cze", "Lip", "Sie", "Wrz", "Paź", "Lis", "Gru"));

?>


<head>
    <meta charset="utf-8">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="public/js/ajax.js"></script>
    <link rel="stylesheet" href="public/css/calendar.css"/>
    <link rel="stylesheet" href="public/css/reservations.css"/>
    <script type="text/javascript"> var url = "<?php echo URL ?>";</script>
</head>
<body>

<div id="container">
kliknij konkretny dzień!

    <?php 
        echo $callendar->show();
        if (!empty($_GET['day']) || !empty($_SESSION['day'])): 
    ?>

        <div id="reservations">
            Dzień: <span id="date"><?php echo $date ?></span>
            <br/>
            <br/>

            <table>
                <?php foreach($times as $time): ?>
                <tr>
                    <td><?php echo $time['time_from'] ?> - <?php echo $time['time_to'] ?></td><td> <button value="<?php echo $time['id'] ?>" class="<?php echo (in_array($time['id'], $resv)) ? 'taken' : 'free'; ?>">
                            <?php echo (in_array($time['id'], $resv)) ? 'zajęte' : 'rezerwuj!'; ?></button></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <div id="hiddenResv">

            </div>
        </div>
        <br/>
        <button id="sendResv" > ZATWIERDŹ </button>



    <?php endif; ?>


</div>

</body>