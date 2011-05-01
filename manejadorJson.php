<?php

session_start();
header('Content-type: application/json; charset=UTF-8');
if (isset($_GET['cumplesMes']))
    echo json_encode ($_SESSION['cumplesMes']);
else if($_GET['eventos'])
    echo json_encode ($_SESSION['eventos']);
?>
