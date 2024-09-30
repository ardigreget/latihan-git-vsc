// popup.js

document.addEventListener('DOMContentLoaded', function() {
    const messageDiv = document.getElementById('message');
    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    const loginError = document.getElementById('loginError');
    const statusDiv = document.getElementById('status');
    const statusMessage = document.getElementById('statusMessage');
    
    // Fungsi untuk generate UUID Browser ID
    function generateUUID() {
        let d = new Date().getTime();
        let d2 = (performance.now() * 1000) || 0;
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            let r = Math.random() * 16;
            if (d > 0) {
                r = (d + r) % 16 | 0;
                d = Math.floor(d / 16);
            } else {
                r = (d2 + r) % 16 | 0;
                d2 = Math.floor(d2 / 16);
            }
            return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
    }

    // Ambil token dan browser_id dari Chrome Storage
    chrome.storage.local.get(['token', 'browser_id'], function(result) {
        if (!result.browser_id) {
            const uuid = generateUUID();
            chrome.storage.local.set({ browser_id: uuid }, function() {
                console.log('Browser ID disimpan:', uuid);
                checkLogin(); // Panggil fungsi check login
            });
        } else {
            checkLogin(); // Panggil fungsi check login
        }
    });

    // Fungsi untuk cek status login
    function checkLogin() {
        const apiUrl = 'https://akses.papindo.id/v1/check_login.php';
        
        chrome.storage.local.get(['token', 'browser_id'], function(result) {
            const token = result.token;
            const browser_id = result.browser_id;

            if (!token) {
                // Belum login, tampilkan form login
                messageDiv.textContent = 'Anda belum login.';
                loginForm.style.display = 'block';
                return;
            }

            // Cek status login di server
            fetch(apiUrl, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'X-Browser-ID': browser_id
                },
                credentials: 'include'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.loggedIn) {
                    if (data.tokenCount < 2) {
                        messageDiv.textContent = `Terimakasih, ${data.username}!`;
                        statusDiv.style.display = 'block';
                        statusMessage.textContent = 'Anda sudah login.';

                    } else {
                        messageDiv.textContent = "Limit maksimum penggunaan extension sudah tercapai.";
                        statusDiv.style.display = 'block';
                    }
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

    // Fungsi untuk login
    loginButton.addEventListener('click', function() {
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();

        if (!username || !password) {
            loginError.textContent = 'Silakan isi semua field.';
            return;
        }

        loginError.textContent = ''; // Reset error message

        // Ambil browser_id dari Chrome Storage
        chrome.storage.local.get(['browser_id'], function(result) {
            const browser_id = result.browser_id;

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
                    // Simpan token di Chrome Storage
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
