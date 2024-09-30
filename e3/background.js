chrome.action.onClicked.addListener(function() {
    // Panggil API untuk mengecek status login
    fetch('http://akses.papindo.id/b3/api.php', { credentials: 'include' })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'error') {
            // Jika belum login, buka halaman login di tab baru
            chrome.tabs.create({ url: 'http://akses.papindo.id/b3/login.php' });
        } else {
            // Jika sudah login, buka popup
            chrome.action.setPopup({ popup: 'popup.html' });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Jika terjadi error, buka halaman login sebagai fallback
        chrome.tabs.create({ url: 'http://akses.papindo.id/b3/login.php' });
    });
});
