function doupload() {
    var file = document.getElementById("file")
    const body = new FormData
    body.append("files[]", "@48_circles.gif")
    
    fetch("https://pomf.lain.la/upload.php", {
      body,
      headers: {
        "Content-Type": "multipart/form-data"
      }
    })
}
