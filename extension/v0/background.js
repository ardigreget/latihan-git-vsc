// Konfigurasi Umum
const DOMAIN = "akses.papindo.id/v1/"; // Ganti dengan domain Anda
const BASE_URL = `https://${DOMAIN}`;

chrome.action.onClicked.addListener((tab) => {
  // Mendapatkan cookie token
  chrome.cookies.get({ url: BASE_URL, name: "token" }, (cookie) => {
    if (cookie) {
      // Cek validitas token melalui API
      fetch(`${BASE_URL}/api.php`, {
        method: 'POST',
        credentials: 'include',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ token: cookie.value })
      })
      .then(response => response.json())
      .then(data => {
        if (data.valid) {
          // Token valid, buka halaman premium dan tampilkan "Terimakasih"
          chrome.tabs.create({ url: `${BASE_URL}/premium.php` });
          chrome.storage.local.set({ message: "Terimakasih" });
        } else {
          // Token tidak valid, arahkan ke login dan tampilkan "Harap login"
          chrome.tabs.create({ url: `${BASE_URL}/login.php` });
          chrome.storage.local.set({ message: "Harap login" });
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
    } else {
      // Tidak ada token, arahkan ke login dan tampilkan "Harap login"
      chrome.tabs.create({ url: `${BASE_URL}/login.php` });
      chrome.storage.local.set({ message: "Harap login" });
    }
  });
});
