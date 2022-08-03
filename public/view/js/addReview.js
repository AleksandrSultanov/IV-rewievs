const elForm = document.querySelector('[name="add"]');
const elNameCreator = elForm.querySelector('[id="name_creator"]');
const elContent = elForm.querySelector('[id="content"]');
const elResult = document.querySelector('#result');
const requestURL = "/api/feedbacks/addAjax";

function addReview() {
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
        elResult.textContent = 'Отзыв добавлен!';
    }
    else {
        alert('Введите данные')
    }
    //location.href = '/api/feedbacks/';
}
window.addEventListener('keydown',function(e){
    if(e.key==="Enter"){
        addReview();
        }},true);