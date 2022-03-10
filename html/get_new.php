<?php
require 'lib.php';
$stud_id = $_POST['stud'];
$lecturer_id = $_POST['lecturer'];
$task_id = $_POST['task'];
$class_name = $_POST['class'];
error_reporting(E_ALL);
ini_set('error_log', '/var/log/php-errors.log');
if ((!ctype_digit($stud_id)) && ($stud_id !=""))
{
    echo json_encode("Ошибка в поле \"ID студента\"");
    error_log("Wrong data on Student ID field", 0);
    exit();
}
if ((!ctype_digit($lecturer_id)) && ($lecturer_id !=""))
{
    echo json_encode("Ошибка в поле \"ID проверяющего\"");
    error_log("Wrong data on Lecturer ID field", 0);
    exit();
}
if ((!ctype_digit($task_id)) && ($task_id !=""))
{
    echo json_encode("Ошибка в поле \"ID задания\"");
    error_log("Wrong data on Task ID field", 0);
    exit();
}
if ((!ctype_alpha($class_name) ) && ($class_name !=""))
{
    echo json_encode("Ошибка в поле \"Название занятия\"");
    error_log("Wrong data on Class Name field", 0);
    exit();
}
list($stud_tasks_answer, $amount_lecturer_answer, 
$task_sg_answer, $class_studs_answer) = get_data($stud_id, $lecturer_id, $task_id, $class_name);

echo json_encode(array(
    array('stud' => $stud_tasks_answer, 'lecturer' => $amount_lecturer_answer, 
        'task' => $task_sg_answer, 'class' => $class_studs_answer), 
    array('stud' => array_keys($stud_tasks_answer[0]), 'lecturer' => array_keys($amount_lecturer_answer[0]),
        'task' => array_keys($task_sg_answer[0]),'class' => array_keys($class_studs_answer[0])), count($amount_lecturer_answer)));
?>
