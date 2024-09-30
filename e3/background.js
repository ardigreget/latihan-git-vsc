chrome.action.onClicked.addListener(function(tab) {
    fetch('http://akses.papindo.id/b3/api.php', { credentials: 'include' })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'error') {
            chrome.tabs.create({ url: 'http://akses.papindo.id/b3/login.php' });
        } else {
            chrome.action.setPopup({ popup: 'popup.html' });
        }
    })
    .catch(error => console.error('Error:', error));
});
