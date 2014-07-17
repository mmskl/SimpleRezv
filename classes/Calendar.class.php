<?php

/**
 * @author  Marcin Moskal
 * @email   moskalmarcin@yahoo.com
 **/
class Calendar
{


    private $_dayLabels = array();
    private $_monthLabels = array();

    private $_next = 'Nastepny';
    private $_prev = 'Poprzedni';

    private $_currentYear;
    private $_currentMonth = 0;
    private $_currentDay = 0;
    private $_currentDate = null;
    private $_daysInMonth = 0;




    public function __construct()
    {
        $this->_dayLabels = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
        $this->_monthLabels = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

    }


    /**
     * @param array $dayLabels
     */
    public function setDayLabels($dayLabels)
    {
        $this->_dayLabels = $dayLabels;
    }

    /**
     * @param array $monthLabels
     */
    public function setMonthLabels($monthLabels)
    {
        $this->_monthLabels = $monthLabels;
    }

    /**
     * @param string $next
     */
    public function setNext($next)
    {
        $this->_next = $next;
    }

    /**
     * @param string $prev
     */
    public function setPrev($prev)
    {
        $this->_prev = $prev;
    }



    /**
     * print out the calendar
     */
    public function show()
    {


        if (isset($_GET['year'])) {

            $year = $_GET['year'];

        } else {

            $year = date('Y', time());

        }

        if (isset($_GET['month'])) {

            $month = $_GET['month'];

        } else {

            $month = date("m", time());

        }

        $this->_currentYear = $year;

        $this->_currentMonth = $month;
        $this->_daysInMonth = $this->_daysInMonth($month, $year);

        $content = '<div id="calendar">' .
            '<div class="box">' .
            $this->_createNavi() .
            '</div>' .
            '<div class="box-content">' .
            '<ul class="label">' . $this->_createLabels() . '</ul>';

        $content .= '<div class="clear"></div>';
        $content .= '<ul class="dates">';

        $weeksInMonth = $this->_weeksInMonth($month, $year);
        // Create weeks in a month

        for ($i = 0; $i < $weeksInMonth; $i++) {

            //Create days in a week
            for ($j = 1; $j <= 7; $j++) {
                $content .= $this->_showDay($i * 7 + $j);
            }
        }

        $content .= '</ul>';

        $content .= '<div class="clear"></div>';

        $content .= '</div>';

        $content .= '</div>';

        return $content;
    }


    /**
     * create the li element for ul
     */
    private function _showDay($cellNumber)
    {

        if ($this->_currentDay == 0) {

            $firstDayOfTheWeek = date('N', strtotime($this->_currentYear . '-' . $this->_currentMonth . '-01'));

            if (intval($cellNumber) == intval($firstDayOfTheWeek)) {

                $this->_currentDay = 1;

            }
        }

        if (($this->_currentDay != 0) && ($this->_currentDay <= $this->_daysInMonth)) {

            $this->_currentDate = date('Y-m-d', strtotime($this->_currentYear . '-' . $this->_currentMonth  . '-' . ($this->_currentDay)));

            $cellContent = $this->_currentDay;

            $this->_currentDay++;

        } else {

            $this->_currentDate = null;

            $cellContent = null;
        }


        return '<li id="li-' . $this->_currentDate . '" class="' . ($cellNumber % 7 == 1 ? 'start' : ($cellNumber % 7 == 0 ? 'end' : ' ')) .
        ($cellContent == null ? 'mask' : '') . '">' . '<a href="' . URL . '?day=' . sprintf("%02d", $cellContent) . '&month=' . sprintf("%02d", $this->_currentMonth) . '&year=' . $this->_currentYear . '"">' . $cellContent . '</a></li>';
    }

    /**
     * create navigation
     */
    private function _createNavi()
    {

        $nextMonth = $this->_currentMonth == 12 ? 1 : intval($this->_currentMonth) + 1;

        $nextYear = $this->_currentMonth == 12 ? intval($this->_currentYear) + 1 : $this->_currentYear;

        $preMonth = $this->_currentMonth == 1 ? 12 : intval($this->_currentMonth) - 1;

        $preYear = $this->_currentMonth == 1 ? intval($this->_currentYear) - 1 : $this->_currentYear;

        return
            '<div class="header">' .
            '<a class="prev" href="' . URL . '?month=' . sprintf('%02d', $preMonth) . '&year=' . $preYear . '">' . $this->_prev . '</a>' .
            '<span class="title">' . date('Y', strtotime($this->_currentYear)) . ' - ' . $this->_monthLabels[intval($this->_currentMonth) - 1] . '</span>' .
            '<a class="next" href="' . URL . '?month=' . sprintf("%02d", $nextMonth) . '&year=' . $nextYear . '">' . $this->_next . '</a>' .
            '</div>';
    }

    /**
     * create calendar week labels
     */
    private function _createLabels()
    {

        $content = '';

        foreach ($this->_dayLabels as $index => $label) {

            $content .= '<li class="' . ($label == 6 ? 'end title' : 'start title') . ' title">' . $label . '</li>';

        }

        return $content;
    }


    /**
     * calculate number of weeks in a particular month
     */
    private function _weeksInMonth($month = null, $year = null)
    {

        if (null == ($year)) {
            $year = date("Y", time());
        }

        if (null == ($month)) {
            $month = date("m", time());
        }

// find number of days in this month
        $daysInMonths = $this->_daysInMonth($month, $year);

        $numOfweeks = ($daysInMonths % 7 == 0 ? 0 : 1) + intval($daysInMonths / 7);

        $monthEndingDay = date('N', strtotime($year . '-' . $month . '-' . $daysInMonths));

        $monthStartDay = date('N', strtotime($year . '-' . $month . '-01'));

        if ($monthEndingDay < $monthStartDay) {

            $numOfweeks++;

        }

        return $numOfweeks;
    }

    /**
     * calculate number of days in a particular month
     */
    private function _daysInMonth($month = null, $year = null)
    {

        if (null == ($year))
            $year = date("Y", time());

        if (null == ($month))
            $month = date("m", time());

        return date('t', strtotime($year . '-' . $month . '-01'));
    }

}