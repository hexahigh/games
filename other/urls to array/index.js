const textInput = document.getElementById("textInput");
const processButton = document.getElementById("processButton");

processButton.addEventListener("click", function () {
  const inputText = textInput.value;
  const lines = inputText.split(/[\n\r]+/);
  console.log(lines);
});
