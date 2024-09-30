document.addEventListener('DOMContentLoaded', function() {
    const messageDiv = document.getElementById('message');
    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    const loginError = document.getElementById('loginError');

    // Ambil browser_id
    chrome.storage.local.get(['browser_id'], function(result) {
        if (!result.browser_id) {
            const browserId = generateUUID();
            chrome.storage.local.set({ browser_id: browserId }, function() {
                checkLoginStatus();
            });
        } else {
            checkLoginStatus();
        }
    });

    // Cek status login
    function checkLoginStatus() {
        chrome.storage.local.get(['token'], function(result) {
            const token = result.token;
            if (!token) {
                loginForm.style.display = 'block';
                return;
            }

            fetch('https://akses.papindo.id/v1/check_login.php', {
                method: 'GET',
                headers: { 'Authorization': `Bearer ${token}`, 'X-Browser-ID': result.browser_id },
                credentials: 'include'
            })
            .then(response => response.json())
            .then(data => {
                if (data.loggedIn) {
                    messageDiv.textContent = `Terimakasih, ${data.username}!`;
                } else {
                    loginForm.style.display = 'block';
                }
            });
        });
    }

    loginButton.addEventListener('click', function() {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        chrome.storage.local.get(['browser_id'], function(result) {
            fetch('https://akses.papindo.id/v1/generate_token.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, password, browser_id: result.browser_id }),
                credentials: 'include'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    chrome.storage.local.set({ token: data.token }, function() {
                        messageDiv.textContent = 'Login berhasil!';
                        loginForm.style.display = 'none';
                    });
                } else {
                    loginError.textContent = data.message;
                }
            });
        });
    });
});
