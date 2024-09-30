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
    const loginButton = document.getElementById('loginButton');
    const actionButton = document.getElementById('actionButton');

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

        chrome.storage.local.get(['token', 'browser_id'], function(result) {
            const token = result.token;
            const browser_id = result.browser_id;
            if (!token || !browser_id) {
                requestToken();
                return;
            }

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
                if (data.loggedIn) {
                    messageDiv.textContent = `Terimakasih, ${data.username}!`;
                } else {
                    messageDiv.textContent = 'Anda belum login atau token tidak valid.';
                    loginButton.style.display = 'inline-block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageDiv.textContent = 'Terjadi kesalahan saat memeriksa status login.';
            });
        });
    }

    function requestToken() {
        chrome.storage.local.get(['browser_id'], function(result) {
            const browser_id = result.browser_id;
            if (!browser_id) {
                messageDiv.textContent = 'Browser ID tidak ditemukan.';
                return;
            }

            fetch('https://akses.papindo.id/v1/generate_token.php?browser_id=' + browser_id, {
                method: 'GET',
                credentials: 'include'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    chrome.storage.local.set({ token: data.token }, function() {
                        messageDiv.textContent = `Terimakasih! Ekstensi aktif.`;
                        actionButton.style.display = 'inline-block';
                        actionButton.textContent = 'Coba Lagi';
                    });
                } else {
                    messageDiv.textContent = data.message;
                    actionButton.style.display = 'inline-block';
                    actionButton.textContent = 'Coba Lagi';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageDiv.textContent = 'Terjadi kesalahan saat mendapatkan token.';
            });
        });
    }

    actionButton.addEventListener('click', function() {
        requestToken();
    });
});
