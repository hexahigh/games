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
    await delay(1000)
    sixPressed = false
}

async function ninePress() {
    ninePressed = true
    await delay(1000)
    ninePressed = false
}

checkSecret()
async function checkSecret() {
    while (doCheck == true) {
        await delay(50)
        if (sixPressed == true) {
            if (ninePressed == true) {
                //document.getElementById("wrapper").style.display = "none";
                document.getElementById("iframeDiv").style.display = "block";
                doCheck = false
            }
        }
    }
}