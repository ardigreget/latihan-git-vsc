// popup.js

document.addEventListener('DOMContentLoaded', function() {
    const messageDiv = document.getElementById('message');
    const loginButton = document.getElementById('loginButton');

    // URL API untuk memeriksa status login
    const apiUrl = 'https://akses.papindo.id/v1/check_login.php';

    fetch(apiUrl, {
        method: 'GET',
        credentials: 'include' // Sertakan cookies
    })
    .then(response => response.json())
    .then(data => {
        if (data.loggedIn) {
            messageDiv.textContent = `Terimakasih, ${data.username}!`;
        } else {
            messageDiv.textContent = 'Anda belum login.';
            loginButton.style.display = 'inline-block';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        messageDiv.textContent = 'Terjadi kesalahan saat memeriksa status login.';
    });
});
