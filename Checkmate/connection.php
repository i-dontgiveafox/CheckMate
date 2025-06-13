<?php
// connection.php

    try {
        $conn = mysqli_connect("localhost", "root", "", "checkmate");
    } catch (mysqli_sql_exception) {
        echo "<script>alert('Connection failed!');</script>";
    }

?>