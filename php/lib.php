<?php
function execute_query($dbconn, $queryText) // Выполнение запроса к БД
{ 
    // Сообщаем MySQL, что данные будут в UTF-8
    $dbconn->query('SET NAMES "utf8"');
    // поготавлием запрос к выполнению и получаем связанный объект 
    $data = $dbconn->prepare($queryText);
    //Выполняем запрос
    $data->execute();
    //Выбираем сразу все троки из запроса
    $answer = $data->fetchAll();
    //Закрываем запрос
    $data = null;
    return $answer;
}
function extract_data() // Выбор нужных массивов данных из БД
{
    // Запросы для выборки данных
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

    // Данные для подключения
    $db_login='phpmyadmin';
    $db_pass='12345'; 
    // Создание объекта подклчюения
    $dbconn = new PDO('mysql:host=localhost;dbname=TestDB', $db_login, $db_pass);
    //Выборка данных в массивы
    $stud_tasks_data = execute_query($dbconn, $q1);
    foreach (execute_query($dbconn, $q2) as $i => $value)
    {
        array_push($stud_tasks_data, $value);
    }
    foreach (execute_query($dbconn, $q3) as $i => $value)
    {
        array_push($stud_tasks_data, $value);
    }
    $lecturer_data = execute_query($dbconn, $q4);
    $class_data = execute_query($dbconn, $q5);
    //Закрытие подключения
    $dbconn = null;
    return array($stud_tasks_data, $lecturer_data, $class_data);
}

function get_stud_tasks($data, $s_id) //Выборка всех заданий студента
{
    //Новый массив для сохранения в него конечной выборки
    $stud_tasks_answer = array();
    //Для каждого студента
    foreach ($data as $i => $value)
    {
        //Если ID студента совпадает с заданным
        if ($value['Student_ID'] == $s_id)
        {
            // Добавить в результирующий массив ID, название и описание задания
            array_push($stud_tasks_answer, array('Task_ID' => $value['Task_ID'], 
            'Name' => $value['Name'], 'Description' => $value['Description']));
        }
    }
    // Убрать повторяющиеся строки
    $stud_tasks_answer = array_unique($stud_tasks_answer, SORT_REGULAR);
    return $stud_tasks_answer;
}

function get_amount_lecturer_answer($s_data, $l_data, $l_id) //Выборка для проверяющего
{
    //Новый массив для сохранения в него конечной выборки
    $amount_lecturer_answer = array();
    //Для каждого проверяющего
    foreach ($l_data as $i => $value)
    {
        //Если его ID совпадает с заданным
        if ($value['Lecturer_ID'] == $l_id)
        {
            //Для каждого студента
            foreach ($s_data as $num => $data)
            {
                //Если ID задания студента совпадает с заданием проверяющего
                if ($data['Task_ID']==$value['Task_ID'])
                {
                    // Добавить в результирующий массив ID, название и описание задания, а также имя и номер группы студента
                    array_push($amount_lecturer_answer, array('Full_name' => $data['Full_name'], 'Number' => $data['Number'], 'Task_ID' => $data['Task_ID'], 
                    'Name' => $data['Name'], 'Description' => $data['Description']));
                }
            }
        }
    }
    return $amount_lecturer_answer;
}
function get_task_sg_answer($data, $t_id) //Выборка студентов для задания
{
    //Новый массив для сохранения в него конечной выборки
    $task_sg_answer = array();
    //Для каждого задания
    foreach ($data as $i => $value)
    {
        //Если Iу студента есть такое задание, то добавить его группу и имя в ответ
        if ($value['Task_ID'] == $t_id)
        {
            
            array_push($task_sg_answer, array('Full_name' => $value['Full_name'], 'Number' => $value['Number']));
        }
    }
    // Убрать повторяющиеся строки
    $task_sg_answer = array_unique($task_sg_answer, SORT_REGULAR);
    return $task_sg_answer;
}

function get_class_studs_answer($data, $c_name) //Выборка студентов для занятия
{
    //Новый массив для сохранения в него конечной выборки
    $class_studs_answer = array();
    //Для каждого студента
    foreach ($data as $i => $value)
    {
        //Если этот студент должен присутствовать
        if ($value['Class_Name'] == $c_name)
        {
            //То добавить его в имя и номер группы в ответ
            array_push($class_studs_answer, array('Full_name' => $value['Full_name'], 'Number' => $value['Number']));
        }
    }
    // Убрать повторяющиеся строки
    $class_studs_answer = array_unique($class_studs_answer, SORT_REGULAR);
    return $class_studs_answer;
}

function get_lecturer_load_answer($l_data, $s_data) // Нагрузка преподавателей
{
    // Массив для ответа
    $lecturer_load_answer = array();
    //Для каждого преподавателя в списке
    foreach ($l_data as $i => $value)
    {
        //Если преподавателя нет в списке
        if (!array_key_exists($value['Full_name']))
        {
            $lecturer_load_answer[$value['Full_name']] = 0;
        }
        // Устанавливаем счётчик равным 0
        $amount = 0;
        //Для каждого студента
        foreach ($s_data as $num => $data)
        {
            // Если у него есть задание этого преподавателя, то увеличить счётчик на 1
            if ($data['Task_ID']==$value['Task_ID'])
            {
                $amount += 1;
            }
        }
        //добавить его в массив в виде ключа и значение установить равным amount
        $lecturer_load_answer[$value['Full_name']] += $amount;
    }
    //Сортировка масива в порядке возрастания
    asort($lecturer_load_answer);
    return $lecturer_load_answer;
}


//Получение данных для вывода
function get_data($s_id = 1, $l_id = 1, $t_id = 3, $c_name = 'Экономика в информационных технологиях') 
{
    //Получаем данные с БД
    list($stud_tasks_data, $lecturer_data, $class_data) = extract_data();

    //Выборка всех заданий студента
    $stud_tasks_answer = get_stud_tasks($stud_tasks_data, $s_id);
    //Выборка для проверяющего
    $amount_lecturer_answer = get_amount_lecturer_answer($stud_tasks_data, $lecturer_data, $l_id);
    //Выборка студентов для задания
    $task_sg_answer = get_task_sg_answer($stud_tasks_data, $t_id);
    //Выборка студентов для занятия
    $class_studs_answer = get_class_studs_answer($class_data, $c_name);
    //Нагрузка преподавателей
    $lecturer_load_answer = get_lecturer_load_answer($lecturer_data, $stud_tasks_data);

    return array($stud_tasks_answer, $amount_lecturer_answer, $task_sg_answer,
     $class_studs_answer, $lecturer_data, $stud_tasks_data, $lecturer_load_answer);
}

?>