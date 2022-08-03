function showReviews() {
    let page = 1;
    // Создаём объект класса XMLHttpRequest
    const request = new XMLHttpRequest();

    /*  Составляем строку запроса и кладем данные*/
    const url = "/api/feedbacks/page/" + page;

    /* Здесь мы указываем параметры соединения с сервером, т.е. мы указываем метод соединения GET,
    а после запятой мы указываем путь к файлу на сервере который будет обрабатывать наш запрос. */
    request.open("GET", url, true);

    // Указываем заголовки для сервера, говорим что тип данных, - контент который мы хотим получить должен быть не закодирован.
    request.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');

    // Здесь мы получаем ответ от сервера на запрос, лучше сказать ждем ответ от сервера
    request.addEventListener("readystatechange", () => {

    /*  request.readyState - возвращает текущее состояние объекта XHR(XMLHttpRequest),
    бывает 4 состояния 4-е состояние запроса - операция полностью завершена, пришел ответ от сервера*/
    if (request.readyState === 4 && request.status === 200) {
        const response = JSON.parse(request.response);
        //console.log(response['result'][5]);
        let html = [];
        for (let i = 0; i < response['result'].length; i++) {
            html.push(`<ol id="" class="rounded"><li><a id="name_creator" href="#">Имя : ${response['result'][i]['name_creator']}</a></li>`);
            html.push(`<li><a id="date_create" href="#">Дата создания : ${response['result'][i]['date_create']}</a></li>`);
            html.push(`<li><a id="content" href="#">Отзыв : ${response['result'][i]['content']}</a></li></ol><hr>`);
            document.querySelector('#result').innerHTML = html.join('');
        }
    }});
    // Выполняем запрос
    request.send();
}