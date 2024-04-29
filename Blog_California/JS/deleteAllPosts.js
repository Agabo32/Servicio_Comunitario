document.getElementById('delete-all-posts').addEventListener('click', function() {
    fetch('PHP/deleteAllPosts.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=deleteAll'
    })
    .then(response => response.text())
    .then(data => {
        if (data === 'success') {
            // Aquí puedes actualizar la interfaz de usuario para reflejar que todos los posts han sido eliminados
            alert('Todos los posts han sido eliminados.');
            // Opcionalmente, puedes actualizar la lista de posts para reflejar que todos han sido eliminados
            document.getElementById('posts').innerHTML = '';
        } else {
            alert('Hubo un error al eliminar los posts.');
        }
    })
    .catch(error => console.error('Error:', error));
});
