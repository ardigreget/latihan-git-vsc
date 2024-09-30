// popup.js

// Fungsi untuk generate UUID
function generateUUID() { // Public Domain/MIT
    var d = new Date().getTime();//Timestamp
    var d2 = (performance && performance.now && (performance.now()*1000)) || 0;//Time in microseconds since page-load or 0 if unsupported
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random() * 16;//random number between 0 and 16
        if(d > 0){
            r = (d + r)%16 | 0;
            d = Math.floor(d/16);
        } else {
            r = (d2 + r)%16 | 0;
            d2 = Math.floor(d2/16);
        }
        return (c==='x' ? r : (r&0x3|0x8)).toString(16);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const messageDiv = document.getElementById('message');
    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    const loginError = document.getElementById('loginError');
    const statusDiv = document.getElementById('status');
    const statusMessage = document.getElementById('statusMessage');
    
    // Inisialisasi browser_id
    chrome.storage.local.get(['browser_id'], function(result) {
        if (!result.browser_id) {
            const uuid = generateUUID();
            chrome.storage.local.set({ browser_id: uuid }, function() {
                console.log('Browser ID disimpan:', uuid);
                checkLogin();
            });
        } else {
            checkLogin();
        }
    });

    function checkLogin() {
        const apiUrl = 'https://akses.papindo.id/v1/check_login.php';

        chrome.storage.local.get(['token'], function(result) {
            const token = result.token;
            if (!token) {
                // Tampilkan form login
                messageDiv.textContent = 'Anda belum login.';
                loginForm.style.display = 'block';
                return;
            }

            fetch(apiUrl, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'X-Browser-ID': result.browser_id
                },
                credentials: 'include'
            })
            .then(response => response.json())
            .then(data => {
                if (data.loggedIn) {
                    messageDiv.textContent = `Terimakasih, ${data.username}!`;
                    statusDiv.style.display = 'block';
                    statusMessage.textContent = `Anda telah login sebagai ${data.roles}.`;
                } else {
                    messageDiv.textContent = 'Token tidak valid atau telah kedaluwarsa.';
                    loginForm.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageDiv.textContent = 'Terjadi kesalahan saat memeriksa status login.';
            });
        });
    }

    loginButton.addEventListener('click', function() {
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;

        if (!username || !password) {
            loginError.textContent = 'Silakan isi semua field.';
            return;
        }

        // Reset error
        loginError.textContent = '';

        // Ambil browser_id
        chrome.storage.local.get(['browser_id'], function(result) {
            const browser_id = result.browser_id;
            if (!browser_id) {
                loginError.textContent = 'Browser ID tidak ditemukan.';
                return;
            }

            // Kirim permintaan ke generate_token.php
            fetch('https://akses.papindo.id/v1/generate_token.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    username: username,
                    password: password,
                    browser_id: browser_id
                }),
                credentials: 'include'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Simpan token
                    chrome.storage.local.set({ token: data.token }, function() {
                        messageDiv.textContent = 'Login berhasil!';
                        loginForm.style.display = 'none';
                        statusDiv.style.display = 'block';
                        statusMessage.textContent = 'Anda telah login.';
                    });
                } else {
                    loginError.textContent = data.message;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                loginError.textContent = 'Terjadi kesalahan saat login.';
            });
        });
    });
});
