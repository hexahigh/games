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
        //document.getElementById("wrapper").style.display = "none";
        document.getElementById("iframeDiv").style.display = "block";

    }
}
