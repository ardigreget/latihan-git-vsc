chrome.action.onClicked.addListener(function(tab) {
    chrome.cookies.get({ url: 'http://akses.papindo.id', name: 'session_id' }, function(cookie) {
        if (!cookie) {
            chrome.tabs.create({ url: 'http://akses.papindo.id/b3/login.php' });
        } else {
            chrome.action.setPopup({ popup: 'popup.html' });
        }
    });
});
