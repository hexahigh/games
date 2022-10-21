document.body.onkeydown = function( e ) {
    var keys2 = {
        65: 'left2',
        68: 'right2',
        83: 'down2',
        87: 'rotate2',
    };
    if ( typeof keys2[ e.keyCode ] != 'undefined' ) {
        keyPress2( keys2[ e.keyCode ] );
        render();
    }
};
