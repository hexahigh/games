// program to print prime numbers between the two numbers
function generatePrime() {
    // take input from the user
    const lowerNumber = parseInt(prompt('Enter lower number: '));
    const higherNumber = parseInt(prompt('Enter higher number: '));
    document.getElementById("Text").innerText = ""

    console.log(`The prime numbers between ${lowerNumber} and ${higherNumber} are:`);

    // looping from lowerNumber to higherNumber
    for (let i = lowerNumber; i <= higherNumber; i++) {
        let flag = 0;

        // looping through 2 to user input number
        for (let j = 2; j < i; j++) {
            if (i % j == 0) {
                flag = 1;
                break;
            }
        }

        // if number greater than 1 and not divisible by other numbers
        if (i > 1 && flag == 0) {
            console.log(i);
            OutText = document.getElementById("Text").innerText;
            OutText2 = OutText + " " + i;
            document.getElementById("Text").innerText = OutText2
        }
    }
}