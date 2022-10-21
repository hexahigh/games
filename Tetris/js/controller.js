document.body.onkeydown = function( e ) {
    var keys = {
        37: 'left',
        39: 'right',
        40: 'down',
        38: 'rotate',
        32: 'drop'
    };
    var keys = {
        65: 'left2',
        68: 'right2',
        83: 'down2',
        87: 'rotate2',
    };
    if ( typeof keys[ e.keyCode ] != 'undefined' ) {
        keyPress( keys[ e.keyCode ] );
        render();
    }
};
document.body.onkeydown = function( e ) {
    var keys2 = {
        65: 'left2',
        68: 'right2',
        83: 'down2',
        87: 'rotate2',
    };
    if ( typeof keys2[ e.keyCode ] != 'undefined' ) {
        keyPress( keys2[ e.keyCode ] );
        render();
    }
};
