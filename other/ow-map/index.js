const planets = document.querySelectorAll('.planet')
let radians = new Array(planets.length).fill(0)
setInterval(() => {
  planets.forEach((planet, index) => {
    let x = Math.cos(radians[index]) * 100; // 100 is the radius of the orbit
    let y = Math.sin(radians[index]) * 100; // 100 is the radius of the orbit
    planet.style.transform = `translate(${x}px, ${y}px)`;
    radians[index] += 0.02 // Adjust this value to change speed
  })
}, 1000/60)