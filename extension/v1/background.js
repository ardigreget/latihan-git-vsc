chrome.runtime.onInstalled.addListener(() => {
    console.log('Extension terpasang');
});

// Listener untuk cek apakah sudah login
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
    if (request.action === 'checkLogin') {
        checkLoginStatus(sendResponse);
        return true; // menandakan bahwa response asynchronous
    }
});

function checkLoginStatus(sendResponse) {
    chrome.cookies.getAll({ domain: 'akses.papindo.id' }, (cookies) => {
        const loginCookie = cookies.find(cookie => cookie.name === 'your_login_cookie_name');
        if (loginCookie) {
            sendResponse({ loggedIn: true });
        } else {
            sendResponse({ loggedIn: false });
        }
    });
}
