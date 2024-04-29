document.addEventListener('DOMContentLoaded', (event) => {
  document.querySelectorAll('.delete-post-btn').forEach(button => {
    button.addEventListener('click', function() {
      const postId = this.getAttribute('data-post-id');
      if (confirm('¿Estás seguro de que quieres eliminar este post?')) {
        fetch('PHP/deletePost.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `post_id=${postId}` 
        })
        .then(response => response.text())
        .then(data => {
          if (data === 'success') {
            document.querySelector(`.post[data-post-id="${postId}"]`).remove();
            alert('El post ha sido eliminado.');
          } else {
            alert('Hubo un error al eliminar el post.');
          }
        })
        .catch(error => console.error('Error:', error));
      }
    });
  });
});
