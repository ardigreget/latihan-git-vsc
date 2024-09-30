chrome.action.onClicked.addListener(function(tab) {
    fetch('http://akses.papindo.id/b3/api.php', { credentials: 'include' })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'error') {
            // Jika tidak login, buka halaman login di tab baru
            chrome.tabs.create({ url: 'http://akses.papindo.id/b3/login.php' });
        } else {
            // Jika sudah login, buka popup
            chrome.action.setPopup({ popup: 'popup.html' });
            chrome.action.openPopup(); // Memastikan popup dibuka
        }
    })
    .catch(error => console.error('Error:', error));
});
