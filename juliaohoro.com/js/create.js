document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('form');
    var imageInput = document.getElementById('image');

    form.addEventListener('submit', function (event) {
        var nameInput = document.getElementById('name');
        var descriptionInput = document.getElementById('description');

        if (nameInput.value.trim() === '') {
            alert('Please enter a name.');
            event.preventDefault();
            return;
        }

        if (imageInput.value.trim() === '') {
            alert('Please enter an image URL.');
            event.preventDefault();
            return;
        }

        if (descriptionInput.value.trim() === '') {
            alert('Please enter a description.');
            event.preventDefault();
            return;
        }
    });
});
