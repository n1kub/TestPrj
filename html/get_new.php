<?php
require 'lib.php';
$stud_id = $_POST['stud'];
$lecturer_id = $_POST['lecturer'];
$task_id = $_POST['task'];
$class_id = $_POST['class'];

list($stud_tasks_answer, $amount_lecturer_answer, 
$task_sg_answer, $class_studs_answer) = get_data($stud_id, $lecturer_id, $task_id, $class_id);

echo json_encode(array(
    array('stud' => $stud_tasks_answer, 'lecturer' => $amount_lecturer_answer, 
        'task' => $task_sg_answer, 'class' => $class_studs_answer), 
    array('stud' => array_keys($stud_tasks_answer[0]), 'lecturer' => array_keys($amount_lecturer_answer[0]),
        'task' => array_keys($task_sg_answer[0]),'class' => array_keys($class_studs_answer[0])), count($amount_lecturer_answer)));
?>
