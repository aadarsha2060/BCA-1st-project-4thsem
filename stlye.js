// Store symptoms in an array
let symptoms = [];

// DOM elements
const symptomInput = document.getElementById("symptomInput");
const symptomTags = document.getElementById("symptomTags");
const resultsDiv = document.getElementById("results");

// Function to render symptoms
function renderSymptoms() {
    symptomTags.innerHTML = "";

    if (symptoms.length === 0) {
        symptomTags.innerHTML = `<span style="color: rgb(162, 4, 202); font-style: italic;">Your symptoms will appear here...</span>`;
        return;
    }

    symptoms.forEach((symptom, index) => {
        const span = document.createElement("span");
        span.className = "symptom-chip";
        span.textContent = symptom;
        span.onclick = () => removeSymptom(index); // remove on click
        symptomTags.appendChild(span);
    });
}

// Function to add a symptom
function addSymptom(symptom) {
    symptom = symptom.trim().toLowerCase();
    if (symptom !== "" && !symptoms.includes(symptom)) {
        symptoms.push(symptom);
        renderSymptoms();
    }
}

// Function to remove a symptom
function removeSymptom(index) {
    symptoms.splice(index, 1);
    renderSymptoms();
}

// Add symptom on Enter key press
symptomInput.addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        addSymptom(symptomInput.value);
        symptomInput.value = "";
    }
});

// Analyze symptoms (fake analysis for demo)
function analyzeSymptoms() {
    if (symptoms.length === 0) {
        resultsDiv.innerHTML = "<p>Please add at least one symptom to analyze.</p>";
        return;
    }

    let analysis = "Based on your symptoms: <strong>" + symptoms.join(", ") + "</strong><br><br>";
    
    // Fake analysis
    const possibleConditions = [];
    if (symptoms.includes("fever")) possibleConditions.push("Flu", "Common Cold", "COVID-19");
    if (symptoms.includes("cough")) possibleConditions.push("Bronchitis", "Flu", "Common Cold");
    if (symptoms.includes("headache")) possibleConditions.push("Migraine", "Tension Headache", "Flu");
    if (symptoms.includes("fatigue")) possibleConditions.push("Anemia", "Flu", "Thyroid Issues");
    if (possibleConditions.length === 0) possibleConditions.push("General Checkup Recommended");

    analysis += "<strong>Possible Conditions:</strong> " + [...new Set(possibleConditions)].join(", ");
    resultsDiv.innerHTML = analysis;
}
