document.getElementById('emiForm').addEventListener('submit', function(event) {
    event.preventDefault();

    // Get input values
    const loanAmount = parseFloat(document.getElementById('loanAmount').value);
    const interestRate = parseFloat(document.getElementById('interestRate').value);
    const loanTenure = parseFloat(document.getElementById('loanTenure').value);

    // Validate inputs
    if (isNaN(loanAmount) || loanAmount <= 0) {
        alert("Please enter a valid loan amount (positive number).");
        return;
    }
    if (isNaN(interestRate) || interestRate <= 0) {
        alert("Please enter a valid interest rate (positive number).");
        return;
    }
    if (isNaN(loanTenure) || loanTenure <= 0) {
        alert("Please enter a valid loan tenure (positive number).");
        return;
    }

    // Calculate monthly interest rate
    const monthlyInterestRate = (interestRate / 12) / 100;

    // Calculate EMI
    const emi = (loanAmount * monthlyInterestRate * Math.pow(1 + monthlyInterestRate, loanTenure)) /
                (Math.pow(1 + monthlyInterestRate, loanTenure) - 1);

    // Calculate total interest and total amount
    const totalInterest = (emi * loanTenure) - loanAmount;
    const totalAmount = emi * loanTenure;

    // Display the EMI, total interest, and total amount
    document.getElementById('emiValue').textContent = `₹${emi.toFixed(2)}`;
    document.getElementById('totalInterest').textContent = `₹${totalInterest.toFixed(2)}`;
    document.getElementById('totalAmount').textContent = `₹${totalAmount.toFixed(2)}`;

    // Generate month-by-month breakdown
    generateBreakdown(loanAmount, monthlyInterestRate, emi, loanTenure);
});

function generateBreakdown(loanAmount, monthlyInterestRate, emi, loanTenure) {
    const tableBody = document.querySelector('#breakdownTable tbody');
    tableBody.innerHTML = ''; // Clear previous results

    let remainingBalance = loanAmount;
    let totalInterestPaid = 0;

    for (let month = 1; month <= loanTenure; month++) {
        const interest = remainingBalance * monthlyInterestRate;
        const principal = emi - interest;
        remainingBalance -= principal;
        totalInterestPaid += interest;

        // Ensure remaining balance doesn't go below zero
        if (remainingBalance < 0) {
            remainingBalance = 0;
        }

        // Create a new row for the table
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${month}</td>
            <td>₹${principal.toFixed(2)}</td>
            <td>₹${interest.toFixed(2)}</td>
            <td>₹${remainingBalance.toFixed(2)}</td>
        `;

        tableBody.appendChild(row);
    }
}