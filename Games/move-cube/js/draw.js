function setup() {
    createCanvas(displayWidth, displayHeight);
}

var rectX = 200;
var rectY = 200;

function draw() {
    background(220);
    //For (var BEGIN; END; INTERVAL){
    //DO SOMETHING }
    for (var x = 0; x < width; x += width / 40) {
        for (var y = 0; y < height; y += height / 40) {
            stroke(0);
            strokeWeight(1);
            line(x, 0, x, height);
            line(0, y, width, y);
        }
    }
    if (keyReleased) {
        if (keyCode == RIGHT_ARROW) {
         rectX +=40; 
        }  else if (keyCode == LEFT_ARROW) {
          rectX -= 40;
        } else if (keyCode == UP_ARROW) {
          rectY -= 40; 
        } else if (keyCode == DOWN_ARROW) {
          rectY +=40; 
        }
       }
    rect(rectX, rectY, 30, 20, 40, 40, 20);
}