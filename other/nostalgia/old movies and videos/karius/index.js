var video = document.getElementById('video');

var sources = [
    {
        'mp4': 'https://data.boof.eu.org/Karius og Baktus h264.mp4',
        'webm':'https://data.boof.eu.org/Karius og Baktus.webm',
        'ogg':'https://data.boof.eu.org/Karius og Baktus.ogv'
    }, 
    {
        'mp4': 'https://archive.org/download/karius-og-baktus/Karius%20og%20Baktus.mp4',
        'webm':'https://archive.org/download/karius-og-baktus/Karius%20og%20Baktus.webm',
        'ogg':'https://archive.org/download/karius-og-baktus/Karius%20og%20Baktus.ogv' 
    }
];

function switchVideo(index) {
    var s = sources[index], source, i;
    video.innerHTML = '';
    for (i in s) {
        source = document.createElement('source');
        source.src = s[i];
        source.setAttribute('type', 'video/' + i);
        video.appendChild(source);
    }
    video.load();
    video.play();
}

document.getElementById('cdn').addEventListener('click', function() {
    switchVideo(0);
}, false);

document.getElementById('archive').addEventListener('click', function() {
    switchVideo(1);
}, false);

document.getElementById('catbox').addEventListener('click', function() {
    switchVideo(2);
}, false);