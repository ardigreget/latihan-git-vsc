document.addEventListener('DOMContentLoaded', () => {
    chrome.storage.local.get('message', (data) => {
      document.getElementById('message').textContent = data.message || "Memeriksa...";
    });
  });
  