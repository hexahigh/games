function setup() {
    createCanvas(40, 40);
}

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
}