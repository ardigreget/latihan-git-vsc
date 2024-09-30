// popup.js

document.addEventListener('DOMContentLoaded', function() {
    const backendUrl = 'http://akses.papindo.id/b4'; // Pastikan ini menggunakan HTTPS jika memungkinkan

    fetch(`${backendUrl}/api.php`, {
        credentials: 'include' // Untuk menyertakan cookie
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        const content = document.getElementById('content');
        if(data.logged_in) {
            content.innerText = "Terima kasih, " + data.username;
        } else {
            content.innerText = "Tidak login. Mengarahkan ke halaman login...";
            // Redirect ke halaman login
            chrome.tabs.create({ url: `${backendUrl}/login_page.html` });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const content = document.getElementById('content');
        content.innerText = "Terjadi kesalahan: " + error.message;
    });
});
