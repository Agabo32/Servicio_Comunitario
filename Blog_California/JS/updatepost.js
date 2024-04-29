window.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById('form').addEventListener('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);

        fetch('PHP/upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // Actualizar los posts después de enviar el formulario
            fetch('PHP/show.php')
                .then(response => response.text())
                .then(data => document.getElementById('posts').innerHTML = data);
        });
    });
});
