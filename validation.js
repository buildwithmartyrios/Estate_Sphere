// --- 1. Balloon Animation Logic ---
function animateBalloon() {
    const balloon = document.querySelector('.balloon');

    // Only proceed if the balloon element actually exists on the page
    if (!balloon) return;

    // Use a local variable for the position if it's not defined globally
    if (typeof window.balloonY === 'undefined') {
        window.balloonY = -250;
    }

    window.balloonY += 1.5; // Speed

    balloon.style.bottom = window.balloonY + 'px';

    // Reset when it leaves the top of the screen
    if (window.balloonY > window.innerHeight) {
        window.balloonY = -250;
    }

    requestAnimationFrame(animateBalloon);
}

// --- 2. ROI Calculator Logic ---
/**
 * Formula: ROI = ((Monthly Rent * 12) - Annual Expenses) / Purchase Price * 100
 */
function calculateROI() {
    const priceInput = document.getElementById('purchasePrice');
    const rentInput = document.getElementById('monthlyRent');
    const expensesInput = document.getElementById('expenses');

    // Basic validation to ensure elements exist
    if (!priceInput || !rentInput) return;

    const price = parseFloat(priceInput.value);
    const rent = parseFloat(rentInput.value);
    const expenses = parseFloat(expensesInput.value) || 0;

    if (price > 0 && rent > 0) {
        // Calculations
        const annualGrossIncome = rent * 12;
        const netIncome = annualGrossIncome - expenses;
        const roi = (netIncome / price) * 100;

        // Selection of display elements
        const resultDiv = document.getElementById('roiResult');
        const percentageDisplay = document.getElementById('roiPercentage');
        const textDisplay = document.getElementById('roiText');

        if (resultDiv && percentageDisplay && textDisplay) {
            resultDiv.style.display = 'block';
            percentageDisplay.innerText = roi.toFixed(2) + "%";

            // Contextual Feedback
            if (roi >= 8) {
                textDisplay.innerText = "Excellent investment! High market return.";
                textDisplay.style.color = "#2ecc71"; // Success Green
            } else if (roi >= 5) {
                textDisplay.innerText = "Stable investment. Good for long-term growth.";
                textDisplay.style.color = "#1a2a6c"; // Navy
            } else {
                textDisplay.innerText = "Lower than average return for this sector.";
                textDisplay.style.color = "#666"; // Gray
            }
        }
    } else {
        alert("Please enter valid numbers for Purchase Price and Monthly Rent.");
    }
}

// --- 3. Initialization ---
window.addEventListener('load', () => {
    // Start animation if the balloon exists
    if (document.querySelector('.balloon')) {
        animateBalloon();
    }
});