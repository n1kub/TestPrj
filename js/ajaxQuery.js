function displayNewData(data)
{
    //Преобразование json в объект
    var jsonData = JSON.parse(data);
    // console.log(jsonData[1])

    //Если в ответе первое значение - null
    if (!jsonData[0])
    {
        //Вывести сообщение об ошибке с сервера
        alert(jsonData[1])
        //Прервать выполнение
        return;
    }

    // для заголовкой каждой таблицы
    $.each(jsonData[1],function(i){
        // console.log(jsonData[1][i])
        // console.log(i)

        // Очистить содержимое таблицы
        $(`#${i} table`).empty();
        //Вставить пустое тело и строку
        $(`#${i} table`).append('<tbody><tr><tr></tbody>');
        // Для каждой заголовочной строки
        $.each(jsonData[1][i], function(ind)
        {
            // console.log(jsonData[1][i][ind])
            
            //Вставить в последнюю строку ячейку заголовка с этиим элементом
            $(`#${i} tr:last`).append(`<th>${jsonData[1][i][ind]}</th>`);
        });
    });
    // для каждой таблицы в массиве данных 
    $.each(jsonData[0],function(i){
        // console.log(i)  

        // Для каждой строки 
        $.each(jsonData[0][i], function(index){
            // вставить новую строку
            $(`#${i} tbody`).append('<tr></tr>');
            // Для каждого элемента данных в строке
            $.each(jsonData[0][i][index], function(ind)
            {
                //Вставить в последнюю строку ячейку с данными
                $(`#${i} tr:last`).append(`<td>${jsonData[0][i][index][ind]}</td>`);
            });
        });
    });
    // Вставка количества абот преподавателя
    $('h5').empty();
    $('h5').append(`Всего работ: ${jsonData[2]}`);
}

$(document).ready(function(){
    //При отправке формы 
    $('#ajaxform').submit(function(e){
        //Отменить стандартное поведение
        e.preventDefault();
        // Отправить ajax
        $.ajax({
            type: "POST",
            url: '/php/get_new.php',
            data: $(this).serialize(),
            success: function(response)
            {
                displayNewData(response);
            }
        });
    });
});