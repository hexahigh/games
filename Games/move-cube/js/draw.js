function setup() {
    createCanvas(windowWidth, windowWidth);
}

var rectX = 0;
var rectY = 0;

function draw() {
    background(220);
    //For (var BEGIN; END; INTERVAL){
    //DO SOMETHING }
    for (var x = 0; x < width; x += width / windowWidth / 20) {
        for (var y = 0; y < height; y += height / windowWidth / 20) {
            stroke(0);
            strokeWeight(1);
            line(x, 0, x, height);
            line(0, y, width, y);
        }
    }
    translate(windowWidth / 2, windowHeight / 2);
    rect(rectX, rectY, 40, 40);
    /*if (keyPressed) {
        if (keyCode == RIGHT_ARROW) {
            rectX += 40;
        } else if (keyCode == LEFT_ARROW) {
            rectX -= 40;
        } else if (keyCode == UP_ARROW) {
            rectY -= 40;
        } else if (keyCode == DOWN_ARROW) {
            rectY += 40;
        }
    }*/
}

function keyReleased() {
    if (keyCode == RIGHT_ARROW) {
        rectX += windowWidth / 20;
    } else if (keyCode == LEFT_ARROW) {
        rectX -= windowWidth / 20;
    } else if (keyCode == UP_ARROW) {
        rectY -= windowWidth / 20;
    } else if (keyCode == DOWN_ARROW) {
        rectY += windowWidth / 20;
    }
}

function windowResized() {
    resizeCanvas(windowWidth, windowWidth);
 }