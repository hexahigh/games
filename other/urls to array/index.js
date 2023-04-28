const textInput = document.getElementById("textInput");
const processButton = document.getElementById("processButton");

processButton.addEventListener("click", function () {
    const inputText = textInput.value;
    const lines = inputText.split(/[\n\r]+/);
    console.log(lines);
    const arr = ['first', 'second', 'third'];
    const modifiedArr = arr.map(i => '"' + i + '",');
    console.log(modifiedArr)
});
