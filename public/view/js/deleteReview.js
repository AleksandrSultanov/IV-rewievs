function deleteReview(id) {
    if (confirm('Вы действительно хотите удалить отзыв?')) {
        let requestURL = "/api/feedbacks/delete/" + id + '/';

        const xhr = new XMLHttpRequest();
        xhr.open('GET', requestURL);

        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = () => {
            if (xhr.status !== 200) {
                return false;
            }
        }
        xhr.send();
        setTimeout(() => {
            location.href = '/api/feedbacks/page/1/';
        }, 1000);
    }
}