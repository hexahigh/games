document.body.onkeydown = function( e ) {
    var keys = {
        37: 'left',
        39: 'right',
        40: 'down',
        38: 'rotate',
        32: 'drop'
    };
    var keys = {
        68: 'left',
        65: 'right',
        83: 'down',
        87: 'rotate',
    };
    if ( typeof keys[ e.keyCode ] != 'undefined' ) {
        keyPress( keys[ e.keyCode ] );
        render();
    }
};
