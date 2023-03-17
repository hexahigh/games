// I have no idea what any of this code does, i just stole it from mozilla dev docs.

const cacheName = "calcu-v1";
const appShellFiles = [
    "../index.html",
    "../styles/dark.css",
    "../styles/light.css",
    "../assets/calculator.ico",
    "../assets/GitHubDark.svg",
    "../assets/GitHubLight.svg",
    "../assets/MoonIcon.svg",
    "../assets/SunIcon.svg",
    "../scripts/saveOffline.js",
    "../scripts/script.js",
    "../scripts/secret.js",
    "../scripts/service-worker.js",
    "../scripts/sw2.js",
    "../secret/index.html",
    "../secret/script.js",
    "../secret/style.css",
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



self.addEventListener("fetch", (e) => {
    e.respondWith(
        (async () => {
            const r = await caches.match(e.request);
            console.log(`[Service Worker] Fetching resource: ${e.request.url}`);
            if (r) {
                return r;
            }
            const response = await fetch(e.request);
            const cache = await caches.open(cacheName);
            console.log(`[Service Worker] Caching new resource: ${e.request.url}`);
            cache.put(e.request, response.clone());
            return response;
        })()
    );
});
