const likeButtons = document.querySelectorAll('.like-button');
likeButtons.forEach(button => {
    button.addEventListener('click', async () => {
        const postId = button.getAttribute('data-post-id');
        try {
            const formData = new FormData();
            formData.append('post_id', postId);

            const response = await fetch('PHP/like.php', {
                method: 'POST',
                body: formData,
                credentials: 'include', // Asegura que las cookies se envíen con la solicitud
            });
            if (response.ok) {
                const data = await response.json();
                if (data.error) {
                    console.error(data.error);
                    // Aquí puedes manejar el error mostrando un mensaje al usuario, por ejemplo:
                    alert(data.error);
                } else if (data.success) {
                    const likeCount = button.querySelector('.like-count');
                    const currentLikes = parseInt(likeCount.textContent, 10);
                    likeCount.textContent = currentLikes + 1;
                }
            } else {
                console.error('Error al dar like');
                // Aquí puedes manejar el error mostrando un mensaje al usuario, por ejemplo:
                alert('Error al dar like');
            }
        } catch (error) {
            console.error('Error de red', error);
            // Aquí puedes manejar el error mostrando un mensaje al usuario, por ejemplo:
            alert('Error de red');
        }
    });
});

