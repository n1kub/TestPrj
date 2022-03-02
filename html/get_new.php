<?php
require 'lib.php';
$stud_id = $_POST['stud'];
$lecturer_id = $_POST['lecturer'];
$task_id = $_POST['task'];
$class_id = $_POST['class'];

$stud_tasks_answer = proc_call('stud_tasks', intval($stud_id));
$amount_lecturer_answer = proc_call('amount_of_studs_for_task_of_lecturer', intval($lecturer_id));
$task_sg_answer = proc_call('task_studs_groups', intval($task_id));
$class_studs_answer = proc_call('class_studs_list', '"'.$class_id.'"');
echo json_encode(array(array('stud' => $stud_tasks_answer, 'lecturer' => $amount_lecturer_answer, 
'task' => $task_sg_answer, 'class' => $class_studs_answer), 
array('stud' => array_keys($stud_tasks_answer[0]), 'lecturer' => array_keys($amount_lecturer_answer[0]),
'task' => array_keys($task_sg_answer[0]),'class' => array_keys($class_studs_answer[0]))));
?>
