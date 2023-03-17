const cacheName = "calcu-v1";
const appShellFiles = [
  "index.html",
  "styles/dark.css",
  "styles/light.css",
  "assets/calculator.ico",
  "assets/GithubDark.svg",
  "assets/GithubLight.svg",
  "assets/MoonIcon.svg",
  "assets/SunIcon.svg",
  "scripts/saveOffline.js",
  "scripts/script.js",
  "scripts/secret.js",
  "scripts/service-worker.js",
  "scripts/sw2.js",
  "secret/index.html",
  "secret/script.js",
  "secret/style.css",
];


self.addEventListener("install", (e) => {
    console.log("[Service Worker] Install");
    e.waitUntil(
      (async () => {
        const cache = await caches.open(cacheName);
        console.log("[Service Worker] Caching all: app shell and content");
        await cache.addAll(appShellFiles);
      })()
    );
  });
  