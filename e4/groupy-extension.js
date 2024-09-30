/* groupy-extension.js | (c) YourName 2023-2024 */
var manifest = chrome.runtime.getManifest();

document.addEventListener("DOMContentLoaded", function() {
    console.log("Login Checker Extension " + manifest.version + "\nYour Extension Description\n\nYour Company © 2023-2024");
    
    const loadingDiv = document.getElementById('loading');
    const contentDiv = document.getElementById('content');

    // Tampilkan loading
    loadingDiv.style.display = 'block';
    contentDiv.style.display = 'none';

    const backendUrl = manifest.homepage_url + "/api.php"; // Endpoint API

    fetch(backendUrl, {
        method: "GET",
        credentials: "include" // Untuk menyertakan cookie
    })
    .then(response => response.json())
    .then(data => {
        // Sembunyikan loading
        loadingDiv.style.display = 'none';
        contentDiv.style.display = 'block';

        if(data.logged_in) {
            contentDiv.innerHTML = `
                <p>Terima kasih, ${data.username}</p>
                <button id="logoutButton">Logout</button>
            `;
            
            // Tambahkan event listener untuk tombol logout
            document.getElementById('logoutButton').addEventListener('click', function() {
                logout(manifest.homepage_url + "/logout.php");
            });
        } else {
            contentDiv.innerHTML = `<p>Tidak login. Mengarahkan ke halaman login...</p>`;
            // Redirect ke halaman login
            chrome.tabs.create({ url: manifest.homepage_url + "/login_page.html" });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Sembunyikan loading
        loadingDiv.style.display = 'none';
        contentDiv.style.display = 'block';
        contentDiv.innerHTML = `<p>Terjadi kesalahan: ${error.message}</p>`;
    });
});

function logout(logoutUrl) {
    fetch(logoutUrl, {
        method: "POST",
        credentials: "include" // Untuk menyertakan cookie
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === "success") {
            chrome.tabs.create({ url: manifest.homepage_url + "/login_page.html" });
        } else {
            alert("Logout gagal: " + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Terjadi kesalahan saat logout: " + error.message);
    });
}
