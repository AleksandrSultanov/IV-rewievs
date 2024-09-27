const elForm = document.querySelector('[name="update"]');
const elNameCreator = elForm.querySelector('[id="name_creator"]');
const elContent = elForm.querySelector('[id="content"]');
const elResult = document.querySelector('#result');


function updateReview(id) {
    let requestURL = "/api/feedbacks/updateAjax/" + id;
    const nameCreator = encodeURIComponent(elNameCreator.value);
    const content = encodeURIComponent(elContent.value);

    if (nameCreator && content) {
        const formData = 'name_creator=' + nameCreator + '&content=' + content;
        const xhr = new XMLHttpRequest();
        xhr.open('POST', requestURL);

        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = () => {
            if (xhr.status !== 200) {
                return false;
            }
        }
        xhr.send(formData);
        elResult.textContent = 'Отзыв обновлен!';
        setTimeout(() => {
            location.href = '/api/feedbacks/';
        }, 100);
    } else {
        alert('Введите данные')
    }
}