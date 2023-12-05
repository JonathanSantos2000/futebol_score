<?php
$daySelect = date("d");
$monthSelect = date("m");
$yearSelec = date("Y");

/* Obter o dia,mes,ano Atual ou passados pelo GET */ {
    if (isset($_GET['day']) || isset($_GET['month']) && isset($_GET['year'])) {
        $daySelect = $_GET['day'];
        $monthSelect = $_GET['month'];
        $yearSelec = $_GET['year'];
    }
}

/* Função para exibir o calendário */
function displayCalendar($month, $year)
{
    /* Obter o primeiro dia do mês e o número de dias do mês */
    $firstDay = mktime(0, 0, 0, $month, 1, $year);
    $nunDays = date("t", $firstDay);

    /* Obter o ultimo dia do mês anterior */
    $lastDayOfPrevMonth = mktime(0, 0, 0, $month - 1, 0, $year);

    /* Obter o ultimo dia do mês  */
    $lastDay = mktime(0, 0, 0, $month, $nunDays, $year);

    /* obter mes e ano anterior e o proximos */
    $prevMonth = date("m", strtotime("-1 month", mktime(0, 0, 0, $month, 1, $year)));
    $prevYear = date("Y", strtotime("-1 month", mktime(0, 0, 0, $month, 1, $year)));
    $nextMonth = date("m", strtotime("+1 month", mktime(0, 0, 0, $month, 1, $year)));
    $nextYear = date("Y", strtotime("+1 month", mktime(0, 0, 0, $month, 1, $year)));
    /* Montando cabeçalho */
    echo '<div class="calendar-header">';
    echo " <a href='?day=01&month={$prevMonth}&year={$prevYear}'><i class='bi bi-caret-left-fill'></i></a>";
    echo '  <h1 class="month">' . date("F Y", $firstDay) . '</h1>';
    echo " <a href='?day=01&month={$nextMonth}&year={$nextYear}'><i class='bi bi-caret-right-fill'></i></a>";
    echo '</div>';
    /* Montando Calendario */
    echo '<table><thead><tr><th>DOM</th><th>SEG</th><th>TER</th><th>QUA</th><th>QUI</th><th>SEX</th><th>SAB</th></tr></thead>';
    echo '<tbody><tr>';
    /* Preencher os espaços em branco no inicio do mês */
    for ($i = 0; $i < date("w", $firstDay); $i++) {
        $prevDay = date("d", $lastDayOfPrevMonth) - date("w", $firstDay) + $i + 1;
        echo "<td class='other-month'>$prevDay</td>";
    }

    /* preencher os dias do mês */
    for ($day = 1; $day <= $nunDays; $day++) {
        $currentDate = mktime(0, 0, 0, $month, $day, $year);
        $currentDay = date("d", $currentDate);
        $currentMonth = date("m", $currentDate);
        echo "<td><a href='?day=$currentDay&month=$currentMonth&year=$year'>$currentDay</a></td>";
        if (date("w", $currentDate) == 6) {
            echo "</tr>";
            if ($day != $nunDays) {
                echo "</tr>";
            }
        }
    }

    /* Preecher os espaços em branco no final do mes */
    for ($i = date("w", $lastDay) + 1; $i < 7; $i++) {
        $nextDay = $i - date("w", $lastDay);
        echo "<td class='other-month'>$nextDay</td>";
    }
    echo "</tr>";
    echo "</table>";
}
