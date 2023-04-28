const textInput = document.getElementById("textInput");
const processButton = document.getElementById("processButton");

var inputText = "NaN"
var lines = "NaN"
var modifiedArr = "NaN"

processButton.addEventListener("click", function () {
    inputText = textInput.value;
    lines = inputText.split(/[\n\r]+/);
    console.log(lines);
    modifiedArr = lines.map(i => '"' + i + '" ');
    console.log(modifiedArr)
});

function addArrayToTextarea() {
    const textarea = document.getElementById("myTextarea");
    textarea.value = modifiedArr
  }
