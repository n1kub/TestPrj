<?php
//Подключить библиотеку
require 'lib.php';
// Получаем данные, пришедшие с ajax
$stud_id = $_POST['stud'];
$lecturer_id = $_POST['lecturer'];
$task_id = $_POST['task'];
$class_name = $_POST['class'];
// error_reporting(E_ALL);

// ini_set("display_errors", 1);

//Проверяем данные на правильность ввода 
if ((!ctype_digit($stud_id)) && ($stud_id !=""))
{
    // Если есть ошибка в данных, отправить ответ в формате array(null, *Сообщение об ошибке*)
    echo json_encode(array(null, "Ошибка в поле \"ID студента\""));
    //Записать в лог сообщение об ошибке. 
    //Параметр "FILE_APPEND" означает, что если файл существует, то в него будут дозаписаны данные, вместо полной перезаписи файла
    file_put_contents("../err_log", "Ошибка в поле \"ID студента\" в ".date("H:m:s d.m.Y").". Введённое значение :".$stud_id."\n",FILE_APPEND);
    exit();
}

//Аналогиная проверка данных и ответ
if ((!ctype_digit($lecturer_id)) && ($lecturer_id !=""))
{
    echo json_encode(array(null, "Ошибка в поле \"ID проверяющего\""));
    file_put_contents("../err_log", "Ошибка в поле \"ID проверяющего\" в ".date("H:m:s d.m.Y").". Введённое значение :".$lecturer_id."\n",FILE_APPEND);
    exit();
}

//Аналогиная проверка данных и ответ
if ((!ctype_digit($task_id)) && ($task_id !=""))
{
    echo json_encode(array(null, "Ошибка в поле \"ID задания\""));
    file_put_contents("../err_log", "Ошибка в поле \"ID задания\" в ".date("H:m:s d.m.Y").". Введённое значение :".$task_id."\n",FILE_APPEND);
    exit();
}

//Аналогиная проверка данных и ответ
if ((!ctype_alpha($class_name) ) && ($class_name !=""))
{
    echo json_encode(array(null, "Ошибка в поле \"Название занятия\""));
    file_put_contents("../err_log", "Ошибка в поле \"Название занятия\" в ".date("H:m:s d.m.Y").". Введённое значение :".$class_name."\n",FILE_APPEND);
    exit();
}

//Получить новые данные с учётом полученных параметров
list($stud_tasks_answer, $amount_lecturer_answer, 
$task_sg_answer, $class_studs_answer) = get_data($stud_id, $lecturer_id, $task_id, $class_name);
 
//Отправить данные обратно
echo json_encode(array(
    array('stud' => $stud_tasks_answer, 'lecturer' => $amount_lecturer_answer, 
        'task' => $task_sg_answer, 'class' => $class_studs_answer), 
    array('stud' => array_keys($stud_tasks_answer[0]), 'lecturer' => array_keys($amount_lecturer_answer[0]),
        'task' => array_keys($task_sg_answer[0]),'class' => array_keys($class_studs_answer[0])), count($amount_lecturer_answer)));
?>
