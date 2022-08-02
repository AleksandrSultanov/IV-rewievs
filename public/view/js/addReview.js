const elForm = document.querySelector('[name="add"]');
const elNameCreator = elForm.querySelector('[id="name_creator"]');
const elContent = elForm.querySelector('[id="content"]');
const elResult = document.querySelector('#result');
const requestURL = "/api/feedbacks/addAjax";

function addReview() {
    const nameCreator = encodeURIComponent(elNameCreator.value);
    const content = encodeURIComponent(elContent.value);
    const formData = 'name_creator=' + nameCreator + '&content=' + content;
    const xhr = new XMLHttpRequest();
    xhr.open('POST', requestURL);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = () => {
        if (xhr.status !== 200) {
            return;
        }
    }
    xhr.send(formData);
    elResult.textContent = 'Отзыв добавлен!';
    //location.href = '/api/feedbacks/';
}