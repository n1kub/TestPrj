<?php
function proc_call($procName, $procArg = NULL)
{
    $db_login='phpmyadmin';
    $db_host='localhost';
    $db_pass='12345'; 
    $db='TestDB';
    $conn=mysqli_connect($db_host,$db_login,$db_pass,$db) or die("Не могу подключиться к БД db: " . db_error());
    mysqli_query($conn, 'SET NAMES "utf8"');
    
    $arg = ($procArg == NULL)? '' : $procArg;
    $query = mysqli_query($conn, "CALL {$procName}({$arg})") or die(mysqli_error($conn));
    $answer=array(mysqli_fetch_assoc($query));
    while($ans=mysqli_fetch_assoc($query))
    {
        array_push($answer, $ans);
    }
    mysqli_close($conn);
    return $answer;
}
?>