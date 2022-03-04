<?php
function proc_call($queryText, $Student_ID = NULL, $Lecturer_ID = NULL, $Task_ID = NULL, $Class_Name = NULL)
{
    $db_login='phpmyadmin';
    $db_host='localhost';
    $db_pass='12345'; 
    $db='TestDB';
    $conn=mysqli_connect($db_host,$db_login,$db_pass,$db) or die("Не могу подключиться к БД db: " . db_error());
    mysqli_query($conn, 'SET NAMES "utf8"');
    $where_clause = "";
    $conditions = compact("Student_ID", "Lecturer_ID", "Task_ID", "Class_Name");
    foreach ($conditions as $i => $value)
    {
        if ($value != NULL)
        {
            $where_clause .= $i."=".$value." ";
            echo "\"".$where_clause."\"<br>";

            
        }
    }
    echo "<br><br><br><br><br>  ";

    // echo "\"".$where_clause."\"<br>";
    $where_clause = trim($where_clause);
    echo "\"".$where_clause."\"<br>";
    // $where_clause = str_replace("\n\n", " and ", $where_clause);
    $where_clause = str_replace(" ", " and ", $where_clause);
    $where_clause = ($where_clause != "") ? "where ".$where_clause : "";
    echo "\"".$where_clause."\"<br>";
    echo "\"".$queryText.$where_clause."\"<br>";

    $arg = ($procArg == NULL)? '' : $procArg;
    $query = mysqli_query($conn, $queryText.$where_clause) or die(mysqli_error($conn));
    $answer=array(mysqli_fetch_assoc($query));
    
    while($ans=mysqli_fetch_assoc($query))
    {
        array_push($answer, $ans);
    }
    print_r($answer);
    mysqli_close($conn);
    
    return $answer;
}
$personal_student_task = "select *
from Student NATURAL JOIN Task_Student NATURAL JOIN Task";

$group_student_task = "select *
from Student NATURAL JOIN Student_Groups NATURAL JOIN `Groups` NATURAL JOIN Task_Group NATURAL JOIN Task";

$class_student_task = "select *
from Student NATURAL JOIN Student_Groups NATURAL JOIN `Groups` NATURAL JOIN Group_Class NATURAL JOIN Class NATURAL JOIN Task_Class NATURAL JOIN Task";

$student_tasks = "select Student_ID,Task_ID,Name,Description from ({$personal_student_task}) as t 
\nUNION\n
select Student_ID,Task_ID,Name ,Description from ({$group_student_task}) as t1
\nUNION\n
select Student_ID,Task_ID,Name,Description from ({$class_student_task}) as t2 \n";

$lec_grs = "select Task_ID,Description, Number,count(Student_ID) from ({$personal_student_task} NATURAL JOIN Task_Lecturer NATURAL JOIN Student_Groups NATURAL JOIN `Groups`) as t 
\nUNION\n
select Task_ID,Description, Number,count(Student_ID) from ({$group_student_task})  as t1
\nUNION\n
select Task_ID,Description, Number,count(Student_ID) from ({$class_student_task}) as t2 \n"

?>