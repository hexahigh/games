//Copies the text from the output
function docopy() {
    document.getElementById("output").select();
    document.execCommand('copy');
}

function doencode() {
    var inputData = document.getElementById("input").value;
    var outputdataP1 = rot13(inputData);
    var outputdataP2 = base64Encode(outputdataP1);
    var outputdata = outputdataP2;
    document.getElementById("output").value = outputdata;
}

function dodecode() {
    var inputData = document.getElementById("input").value;
    var outputdataP1 = rot13(inputData);
    var outputdataP2 = base64Decode(outputdataP1);
    var outputdata = outputdataP2;
    document.getElementById("output").value = outputdata;
}

//Rot13 cipher
function rot13(message) {
    return message.replace(/[a-z]/gi, letter => String.fromCharCode(letter.charCodeAt(0) + (letter.toLowerCase() <= 'm' ? 13 : -13)));
} 

function base64Encode(message) {
    return btoa(message);
}

function base64Decode(message) {
    return atob(message);
}