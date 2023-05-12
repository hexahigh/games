var arrayString = "null"
var arrayMD5 = "null"
var array256 = "null"
var array1 = "null"
var output = "null"
var linesDone = "1"
const outputArea = document.getElementById("outputArea").value

function loadFiles() {
    loadString()
    loadMD5()
    load256()
    load1()
}

function loadMD5() {
    fetch('files/random strings md5.txt')
    .then(response => response.arrayBuffer())
    .then(arrayBuffer => {
        const decoder = new TextDecoder('utf-8');
        const data = decoder.decode(new Uint8Array(arrayBuffer));
        arrayMD5 = data.split('\n');
    })
}

function loadString() {
    fetch('files/random strings.txt')
    .then(response => response.arrayBuffer())
    .then(arrayBuffer => {
        const decoder = new TextDecoder('utf-8');
        const data = decoder.decode(new Uint8Array(arrayBuffer));
        arrayString = data.split('\n');
    })
}

function load1() {
    fetch('files/random strings sha1.txt')
    .then(response => response.arrayBuffer())
    .then(arrayBuffer => {
        const decoder = new TextDecoder('utf-8');
        const data = decoder.decode(new Uint8Array(arrayBuffer));
        arrayMD5 = data.split('\n');
    })
}
function load256() {
    fetch('files/random strings sha256.txt')
    .then(response => response.arrayBuffer())
    .then(arrayBuffer => {
        const decoder = new TextDecoder('utf-8');
        const data = decoder.decode(new Uint8Array(arrayBuffer));
        arrayMD5 = data.split('\n');
    })
}


function mergeToCsv() {
    for (x in arrayString) {
        let length = arrayString.length
        if(linesDone < length) {
            Amd5 = arrayMD5[linesDone];
            A256 = array256[linesDone];
            Astring = arrayString[linesDone];
            A1 = array1[linesDone];
            output = Astring + "," + Amd5 + "," + A1 + "," + A256
            outputArea = outputArea + "\n" + output
        }
        linesDone + 1
    }
}