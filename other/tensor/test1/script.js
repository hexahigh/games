// Load the TensorFlow.js model
async function loadModel() {
  const model = await tf.loadLayersModel("model/model.json");
  return model;
}

function preprocessAudio(filename) {
  // Load audio file
  const audio = new Audio(filename);

  // Generate a Mel-scaled spectrogram
  const spectrogram = tf.tidy(() => {
    const audioTensor = tf.browser.fromAudio(audio);
    const sampleRate = audioTensor.sampleRate();
    const melSpectrogram = tf.signal.melSpectrogram(audioTensor, {
      sampleRate: sampleRate,
      hopLength: 512,
      nMels: 128,
      fMin: 0,
      fMax: 8000
    });
    const logMelSpectrogram = tf.math.log(melSpectrogram.add(1e-6));
    const normalizedSpectrogram = logMelSpectrogram.div(tf.max(logMelSpectrogram));
    return normalizedSpectrogram;
  });

  return spectrogram;
}


// Classify the audio using the loaded model
async function classifyAudio(model, audioData) {
  const preprocessedData = preprocessAudio(audioData);
  const audioTensor = tf.tensor(preprocessedData);
  const predictions = model.predict(audioTensor);
  const predictedClass = predictions.argMax().dataSync()[0];
  return predictedClass;
}

// Handle form submission
document.querySelector("form").addEventListener("submit", async function(event) {
  event.preventDefault();
  const fileInput = document.getElementById("audioFile");
  const file = fileInput.files[0];
  const fileReader = new FileReader();

  fileReader.onload = async function(event) {
    const audioData = event.target.result;

    // Load the model
    const model = await loadModel();

    // Classify the audio
    const classificationResult = await classifyAudio(model, audioData);

    // Display the classification result
    const resultSection = document.getElementById("result");
    resultSection.textContent = `Class: ${classificationResult}`;
  };

  // Read the uploaded audio file
  fileReader.readAsArrayBuffer(file);
});
