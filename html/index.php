
<?php require_once '/var/www/twig/vendor/autoload.php'; ?>
<?php $loader = new Twig_Loader_Filesystem('../html'); ?> 
<?php $twig = new Twig_Environment($loader); ?> 
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
$stud_tasks_answer = proc_call('stud_tasks', 1);
$amount_lecturer_answer = proc_call('amount_of_studs_for_task_of_lecturer', 2);
$task_sg_answer = proc_call('task_studs_groups', 3);
$class_studs_answer = proc_call('class_studs_list', '\'Экономика в информационных технологиях\'');
$lecturer_load_answer = proc_call('lecturer_amount_of_tasks');
$task_load_answer = proc_call('task_load');


echo $twig->render('template.html', array(
    'stud_tasks' => $stud_tasks_answer, 'stud_tasks_keys' => array_keys($stud_tasks_answer[0]),
    'amount_lecturer' => $amount_lecturer_answer, 'amount_lecturer_keys' => array_keys($amount_lecturer_answer[0]),
    'task_sg' => $task_sg_answer, 'task_sg_keys' => array_keys($task_sg_answer[0]),
    'class_studs' => $class_studs_answer, 'class_studs_keys' => array_keys($class_studs_answer[0]),
    'lecturer_load' => $lecturer_load_answer, 
    'task_load' => $task_load_answer)); ?>



