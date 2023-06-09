const client = new WebTorrent()

const magnetUri = 'magnet:?xt=urn:btih:0df01f9d83b9c8cb1b7ee9b6ec726167b85cd143&dn=Karius+og+Baktus.webm&tr=wss%3A%2F%2Ftracker.btorrent.xyz&tr=wss%3A%2F%2Ftracker.openwebtorrent.com&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337&tr=udp%3A%2F%2Fexplodie.org%3A6969&tr=udp%3A%2F%2Ftracker.empire-js.us%3A1337'

function mp4Vid() {
    client.add(magnetUri, {urlList: 'data.boof.eu.org, https://data.boof.eu.org'}, (torrent) => {
        // Find the video file in the torrent's files
        const videoFile = torrent.files.find((file) => file.name.endsWith('.mp4'));

        // Create a blob URL from the video file
        videoFile.getBlobURL((err, url) => {
            if (err) throw err;

            // Set the blob URL as the source for the video element
            const source = document.getElementById('source1');
            source.src = url;
            source.type = "video/mp4";

            // Load and play the video
            const video = document.getElementById('video');
            video.load();
            video.play();
        });
    });
}

function webmVid() {
    client.add(magnetUri, (torrent) => {
        // Find the video file in the torrent's files
        const videoFile = torrent.files.find((file) => file.name.endsWith('.webm'));

        // Create a blob URL from the video file
        videoFile.getBlobURL((err, url) => {
            if (err) throw err;

            // Set the blob URL as the source for the video element
            const source = document.getElementById('source2');
            source.src = url;
            source.type = "video/webm";

            // Load and play the video
            const video = document.getElementById('video');
            video.load();
            video.play();
        });
    });
}

function oggVid() {
    client.add(magnetUri, (torrent) => {
        // Find the video file in the torrent's files
        const videoFile = torrent.files.find((file) => file.name.endsWith('.ogv'));

        // Create a blob URL from the video file
        videoFile.getBlobURL((err, url) => {
            if (err) throw err;

            // Set the blob URL as the source for the video element
            const source = document.getElementById('source3');
            source.src = url;
            source.type = "video/ogg";

            // Load and play the video
            const video = document.getElementById('video');
            video.load();
            video.play();
        });
    });
}