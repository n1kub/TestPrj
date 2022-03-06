<?php
function execute_query($queryText)
{
    $db_login='phpmyadmin';
    $db_host='localhost';
    $db_pass='12345'; 
    $db='TestDB';
    $conn=mysqli_connect($db_host,$db_login,$db_pass,$db) or die("Не могу подключиться к БД db: " . db_error());
    mysqli_query($conn, 'SET NAMES "utf8"');

    $arg = ($procArg == NULL)? '' : $procArg;
    $query = mysqli_query($conn, $queryText.$where_clause) or die(mysqli_error($conn));
    $answer=array(mysqli_fetch_assoc($query));
    
    while($ans=mysqli_fetch_assoc($query))
    {
        array_push($answer, $ans);
    }
    mysqli_close($conn);
    
    return $answer;
}

function get_data($s_id = 1, $l_id = 1, $t_id = 3, $c_name = 'Экономика в информационных технологиях')
{
    $q1 = "select *
    from Student NATURAL JOIN Task_Student NATURAL JOIN Task  NATURAL JOIN 
    Task_Lecturer NATURAL JOIN Student_Groups NATURAL JOIN `Groups` ";

    $q2 = "select *
    from Student NATURAL JOIN Student_Groups NATURAL JOIN `Groups` NATURAL JOIN Task_Group NATURAL JOIN Task ";

    $q3 = "select *
    from Student NATURAL JOIN Student_Groups NATURAL JOIN `Groups` NATURAL JOIN 
    Group_Class NATURAL JOIN Task_Class NATURAL JOIN Task ";

    $q4 = "select *
    from Lecturer NATURAL JOIN Task_Lecturer ";

    $q5 = "select * 
    from Class NATURAL JOIN Group_Class NATURAL JOIN `Groups` NATURAL JOIN Student_Groups NATURAL JOIN Student ";

    $stud_tasks_data = execute_query($q1);
    foreach (execute_query($q2) as $i => $value)
    {
        array_push($stud_tasks_data, $value);
    }
    foreach (execute_query($q3) as $i => $value)
    {
        array_push($stud_tasks_data, $value);
    }
    $lecturer_data = execute_query($q4);
    $class_data = execute_query($q5);

    $stud_tasks_answer = array();
    foreach ($stud_tasks_data as $i => $value)
    {
        if ($value['Student_ID'] == $s_id)
        {
            array_push($stud_tasks_answer, array('Task_ID' => $value['Task_ID'], 
            'Name' => $value['Name'], 'Description' => $value['Description']));
        }
    }
    $stud_tasks_answer = array_unique($stud_tasks_answer, SORT_REGULAR);

    
    $amount_lecturer_answer = array();
    foreach ($lecturer_data as $i => $value)
    {
        if ($value['Lecturer_ID'] == $l_id)
        {
            foreach ($stud_tasks_data as $num => $data)
            {
                if ($data['Task_ID']==$value['Task_ID'])
                {
                    array_push($amount_lecturer_answer, array('Full_name' => $data['Full_name'], 'Number' => $data['Number'], 'Task_ID' => $data['Task_ID'], 
                    'Name' => $data['Name'], 'Description' => $data['Description']));
                }
            }
        }
    }
    $amount_lecturer_answer = array_unique($amount_lecturer_answer, SORT_REGULAR);
    $task_sg_answer = array();
    foreach ($stud_tasks_data as $i => $value)
    {
        
        if ($value['Task_ID'] == $t_id)
        {
            
            array_push($task_sg_answer, array('Full_name' => $value['Full_name'], 'Number' => $value['Number']));
        }
    }
    $task_sg_answer = array_unique($task_sg_answer, SORT_REGULAR);

    $class_studs_answer = array();
    foreach ($class_data as $i => $value)
    {
        if ($value['Class_Name'] == $c_name)
        {
            array_push($class_studs_answer, array('Full_name' => $value['Full_name'], 'Number' => $value['Number']));
        }
    }
    $class_studs_answer = array_unique($class_studs_answer, SORT_REGULAR);

    return array($stud_tasks_answer, $amount_lecturer_answer, $task_sg_answer,
     $class_studs_answer, $lecturer_data, $stud_tasks_data);
}

?>