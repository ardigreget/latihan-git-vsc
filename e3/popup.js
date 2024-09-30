document.addEventListener('DOMContentLoaded', function() {
    chrome.cookies.get({ url: 'http://akses.papindo.id', name: 'session_id' }, function(cookie) {
        if (cookie) {
            document.getElementById('status').innerText = "Sudah login";
        } else {
            document.getElementById('status').innerText = "Belum login";
        }
    });
});
