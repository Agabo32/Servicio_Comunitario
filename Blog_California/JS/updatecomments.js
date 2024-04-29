window.addEventListener('DOMContentLoaded', (event) => {
    // Obtén todos los formularios de comentarios
    var commentForms = document.querySelectorAll('.comment-form');

    // Añade un event listener a cada formulario de comentarios
    commentForms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(this);

            fetch('PHP/comment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Actualizar los comentarios después de enviar el formulario
                var postId = this.elements.post_id.value; // Obtén el ID del post
                fetch('php/show_comments.php?post_id=' + postId)
                    .then(response => response.text())
                    .then(data => {
                        // Asume que cada post tiene un div con la clase 'comments' para mostrar los comentarios
                        document.querySelector('.comments[data-post-id="' + postId + '"]').innerHTML = data;
                    });
            });
        });
    });
});
