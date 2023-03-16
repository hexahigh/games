var sixPressed = false
var ninePressed = false
var doCheck = true

// Set delay to ms
function delay(milliseconds) {
    return new Promise(resolve => {
        setTimeout(resolve, milliseconds);
    });
}

async function sixPress() {
    sixPressed = true
    await delay(500)
    sixPressed = false
}

function ninePress() {
    checkSecret()
}


async function checkSecret() {
    if (sixPressed == true) {
        document.getElementById("websiteWrapper").style.display = "none";
        document.getElementById("iframeDiv").style.display = "block";

    }
}


//adding event handler on the document to handle keyboard inputs
document.addEventListener("keyup", keyboardInputHandler);

//function to handle keyboard inputs
function keyboardInputHandler(o) {
  // to fix the default behavior of browser,
  // enter and backspace were causing undesired behavior when some key was already in focus.
  o.preventDefault();
  //grabbing the liveScreen

  //numbers
  if (o.key === "6") {
    sixPress()
  } else if (o.key === "9") {
    checkSecret()
  }
}