// popup.js

document.addEventListener('DOMContentLoaded', function() {
    const backendUrl = 'http://akses.papindo.id/b4'; // Base URL backend Anda

    fetch(`${backendUrl}/api.php`, {
        credentials: 'include' // Untuk menyertakan cookie
    })
    .then(response => response.json())
    .then(data => {
        const content = document.getElementById('content');
        if(data.logged_in) {
            content.innerText = "Terima kasih, " + data.username;
        } else {
            // Redirect ke halaman login
            chrome.tabs.create({ url: `${backendUrl}/login_page.html` }); // Pastikan Anda memiliki halaman login_page.html
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
