// background.js

let manifest = chrome.runtime.getManifest();

chrome.runtime.onInstalled.addListener(() => {
    chrome.tabs.create({ url: manifest.homepage_url + "/extension.php" });
});
