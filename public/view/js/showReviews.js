function showReviews() {
    const currentUrl = new URL(window.location.href);
    let page = (currentUrl.pathname).replace(/[^0-9]/g, "");

    // Создаём объект класса XMLHttpRequest
    const request = new XMLHttpRequest();

    /*  Составляем строку запроса и кладем данные*/
    const url = "/api/feedbacks/ajax/page/" + page + '/';

    /* Здесь мы указываем параметры соединения с сервером, т.е. мы указываем метод соединения GET,
    а после запятой мы указываем путь к файлу на сервере который будет обрабатывать наш запрос. */
    request.open("GET", url, true);

    // Указываем заголовки для сервера, говорим что тип данных, - контент который мы хотим получить должен быть не закодирован.
    request.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');

    // Здесь мы ждем ответ от сервера
    request.addEventListener("readystatechange", () => {

        /*  request.readyState - возвращает текущее состояние объекта XHR(XMLHttpRequest),
        бывает 4 состояния. 4-е состояние запроса - операция полностью завершена, пришел ответ от сервера*/
        if (request.readyState === 4 && request.status === 200) {
            const response = JSON.parse(request.response);
            let html = [];
            if (response['result']) {
                for (let i = 0; i < response['result'].length; i++) {
                    html.push(`<a href="/api/feedbacks/update/${response['result'][i]['id']}" class="button1" id="update">Обновить отзыв</a>
                            <a href="" onClick='deleteReview(${response['result'][i]['id']})' class="button1" id="delete">Удалить отзыв</a>
                            <ol class="rounded"><li><a id="name_creator" href="#">Имя : ${response['result'][i]['name_creator']}</a></li>
                            <li><a id="date_create" href="#">Дата создания : ${response['result'][i]['date_create']}</a></li>
                            <li><a id="date_change" href="#">Дата изменения : ${response['result'][i]['date_change']}</a></li>
                            <li><a id="content" href="#">Отзыв : ${response['result'][i]['content']}</a></li>
                            <li><a id="rating" href="#">Оценка : ${response['result'][i]['rating']}</a></li></ol><hr>`);
                    document.querySelector('#result').innerHTML = html.join('');
                }
            }
        }

        let html = [];
        html.push(`<ul class="pagination">`);
        for (let i = 4; i > 0; i--) {
            if (page - i > 0) {
                var numPage = Number(page) - Number(i);
                html.push(`<a href="/api/feedbacks/page/` + numPage + `/">` + numPage + `</a> `);
            }
        }
        html.push(`<a class="current" href="/api/feedbacks/page/` + Number(page) + `/">` + Number(page) + `</a> `);
        for (let i = 1; i < 5; i++) {
            var numPage = Number(page) + Number(i);
            html.push(`<a href="/api/feedbacks/page/` + numPage + `/">` + numPage + `</a> `);
        }
        html.push(`</ul>`);
        document.querySelector('#pagenavigation').innerHTML = html.join('');
    });
    // Выполняем запрос
    request.send();
}

showReviews();