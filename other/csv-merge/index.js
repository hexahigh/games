var arrayString = "null"
var arrayMD5 = "null"
var array256 = "null"
var array1 = "null"
var output = "null"
var linesDone = 50000
const outputArea = document.getElementById("outputArea")

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
        array1 = data.split('\n');
    })
}
function load256() {
    fetch('files/random strings sha256.txt')
    .then(response => response.arrayBuffer())
    .then(arrayBuffer => {
        const decoder = new TextDecoder('utf-8');
        const data = decoder.decode(new Uint8Array(arrayBuffer));
        array256 = data.split('\n');
    })
}


function mergeToCsv() {
    console.log("Lenght of array is " + arrayString.length)
    for (x in arrayString) {
        let length = arrayString.length
        if(linesDone < 100000) {
            Amd5 = arrayMD5[linesDone];
            Asha1 = array256[linesDone];
            Astring = arrayString[linesDone];
            Asha256 = array1[linesDone];
            output = Astring + "," + Amd5 + "," + Asha1 + "," + Asha256
            outputArea.value = outputArea.value + "\n" + output
            console.log(linesDone + "/" + arrayString.length)
            linesDone = linesDone + 1
        }
    }
}