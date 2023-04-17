


//Copies the text from the output
function docopy() {
    document.getElementById("output").select();
    document.execCommand('copy');
}

function doencode() {
    var inputData = document.getElementById("input").value;
    var outputdata = rot13(inputData);
    document.getElementById("output").value = outputdata;
}

function dodecode() {
    var inputData = document.getElementById("input").value;
    var outputdata = rot13(inputData);
    document.getElementById("output").value = outputdata;
}

//Rot13 cipher
function rot13(message) {
    return message.replace(/[a-z]/gi, letter => String.fromCharCode(letter.charCodeAt(0) + (letter.toLowerCase() <= 'm' ? 13 : -13)));
} 
