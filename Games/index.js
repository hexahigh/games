function sortList(ul) {
    var ul = document.getElementById(ul);
  
    Array.from(ul.getElementsByTagName("A"))
      .sort((a, b) => a.textContent.localeCompare(b.textContent))
      .forEach(li => ul.appendChild(li));
  }