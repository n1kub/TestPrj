
<?php 
// phpinfo();
//Стартовые действия
//===============================================================================================
//Подключаем файл автозагрузки php для twig
require_once '/var/www/twig/vendor/autoload.php';
// Соаём экземпляр загрузчика шаблонов, который ищет шаблоны в /TestPrj/html
$loader = new Twig_Loader_Filesystem('/TestPrj/html'); 
//Создаём экземпляр среды
$twig = new Twig_Environment($loader); 
// Подключаем библиотеку с функциями
require 'php/lib.php';


//Получение данных
//===============================================================================================
list($stud_tasks_answer, $amount_lecturer_answer, $task_sg_answer, $class_studs_answer, $lecturer_data, 
$stud_tasks_data, $lecturer_load_answer) = get_data();









// echo '<pre>';
// print_r($lecturer_data);
// echo '</pre>';

//Выполнение некоторых рассчётов для получения статистики
//===============================================================================================
//Создаём массив, в котором будет храниться 
//*Номер задания* : [*Название задания*, *количество студентов*] и заоплняем
$studs_amount = array();
foreach ($stud_tasks_data as $i => $value)
{
    if (!array_key_exists($value['Task_ID']))
    {
        $studs_amount[$value['Task_ID']] = array($value['Name'], 0);
    }
}

//Считаем количество студентов для каждого задания
foreach ($stud_tasks_data as $num => $data)
{
    $studs_amount[$data['Task_ID']][1] += 1;
}

//Создаём массив, в котором будет храниться 
//*Номер задания* : *кол-во проверяющих* и заполняем
$lecs_amount = array();
foreach ($lecturer_data as $i => $value)
{
    if (!array_key_exists($value['Task_ID']))
    {
        $lecs_amount[$value['Task_ID']] = 0;
    }
}

//Считаем количество проверяющих для каждого задания
foreach ($lecturer_data as $num => $data)
{
    $lecs_amount[$data['Task_ID']] += 1;
}


//Создаём массив, в котором будет храниться конечный результат
//*Номер задания* : [*Название задания*, *количество студентов*/*кол-во проверяющих*] и заполняем 
$task_load_answer = array();
foreach ($lecs_amount as $i => $value)
{
    $task_load_answer[$i] = array($studs_amount[$i][0], $studs_amount[$i][1]/$value);
}

//Вывод полученных данных в шаблон twig
//===============================================================================================
echo $twig->render('template.html.twig', array(
    'stud_tasks' => $stud_tasks_answer, 'stud_tasks_keys' => array_keys($stud_tasks_answer[0]),
    'amount_lecturer' => $amount_lecturer_answer, 'amount_lecturer_keys' => array_keys($amount_lecturer_answer[0]), 'lec_am' => count($amount_lecturer_answer),
    'task_sg' => $task_sg_answer, 'task_sg_keys' => array_keys($task_sg_answer[0]),
    'class_studs' => $class_studs_answer, 'class_studs_keys' => array_keys($class_studs_answer[0]),
    'lecturer_load' => $lecturer_load_answer, 
    'task_load' => $task_load_answer)
); 

?>