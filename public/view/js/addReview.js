const elForm = document.querySelector('[name="add"]');
const elNameCreator = elForm.querySelector('[id="name_creator"]');
const elContent = elForm.querySelector('[id="content"]');
const elRating = elForm.querySelector('[id="rating"]');
const elResult = document.querySelector('#result');
const requestURL = "/api/feedbacks/addAjax";

function addReview() {
    const nameCreator = encodeURIComponent(elNameCreator.value);
    const content = encodeURIComponent(elContent.value);
    const rating = encodeURIComponent(elRating.value);
    if (nameCreator && content) {
        const formData = 'name_creator=' + nameCreator + '&content=' + content + '&rating=' + rating;
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
        setTimeout(() => {
            location.href = '/api/feedbacks/page/1/';
        }, 300);
    } else {
        alert('Введите данные')
    }
}

window.addEventListener('keydown', function (e) {
    if (e.key === "Enter") {
        addReview();
    }
}, true);