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


async function checkSecret() {
    while (doCheck == true) {
        await delay(50)
        if (sixPressed == true && ninePressed == true) {
            //document.getElementById("bodyId").innerHTML = "whatever";
            document.write(<div style="position:absolute; left: 0; right: 0; bottom: 0; top: 0px;">
                <iframe sandbox="allow-scripts allow-popups" width="100%" height="100%" frameborder="0" src="https://herremann.edu.eu.org"></iframe>
            </div>)
        }
    }
}