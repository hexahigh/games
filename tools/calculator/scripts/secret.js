var sixPressed = false
var ninePressed = false
var doCheck = true
var tenPressed = false

// Set delay to ms
function delay(milliseconds) {
    return new Promise(resolve => {
        setTimeout(resolve, milliseconds);
    });
}

// Code for 69 secret
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

// Code for getting keyboard input

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
    ninePress2()
  }
  else if (o.key === "10") {
    tenPress()
    checkSecret2()
  }
}

// Code for 21 secret
async function ninePress2() {
  ninePressed2 = true
  await delay(500)
  ninePressed2 = false
}


async function checkSecret2() {
  if (ninePressed2 == true) {
    const audio21 = new Audio('assets/21.mp3');
    audio21.play();

  }
}