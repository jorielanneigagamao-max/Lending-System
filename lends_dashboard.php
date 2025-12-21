<?php

$annualInterestRate = 0.12; 
$monthlyInterestRate = $annualInterestRate / 12;

$loanAmount = isset($_POST['loan_amount']) ? (float)$_POST['loan_amount'] : '';
$loanTerm = isset($_POST['loan_term']) ? (int)$_POST['loan_term'] : '';
$errorMessage = '';
$monthlyPayment = 0;
$totalPayment = 0;
$totalInterest = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($loanAmount < 500 || $loanAmount > 50000) {
        $errorMessage = "⚠️ Loan amount must be between ₱500 and ₱50,000.";
    } elseif (!in_array(needle: $loanTerm, haystack: [1,3,6,9,12,24])) {
        $errorMessage = "⚠️ Please select a valid loan term.";
    } else {
        if ($monthlyInterestRate > 0) {
            $monthlyPayment = $loanAmount * ($monthlyInterestRate * pow(num: 1 + $monthlyInterestRate, exponent: $loanTerm)) / (pow(num: 1 + $monthlyInterestRate, exponent: $loanTerm) - 1);
        } else {
            $monthlyPayment = $loanAmount / $loanTerm;
        }
        $totalPayment = $monthlyPayment * $loanTerm;
        $totalInterest = $totalPayment - $loanAmount;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Loan Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #000000ff; }
        .container { max-width: 600px; margin: auto; background: #fff5f5ff; padding: 20px; border-radius: 8px; }
        h2 { text-align: center; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; }
        .result { margin-top: 20px; background: rgba(122, 122, 122, 1); padding: 15px; border-radius: 6px; }
        .error { margin-top: 20px; background: #fdd; padding: 15px; border-radius: 6px; color: #900; }
    </style>
</head>
<body>
<div class="container">
    <h2> Lending Loan System </h2>
    <form method="post">
        <label>Loan Amount (₱500 - ₱50,000):</label>
        <input type="number" name="loan_amount" min="500" max="50000" 
               value="<?php echo htmlspecialchars(string: $loanAmount); ?>" required>

        <label>Loan Term (months):</label>
        <select name="loan_term" required>
            <option value="">-- Select Term --</option>
            <?php
            foreach ([1,3,6,9,12,24] as $term) {
                $selected = ($loanTerm == $term) ? 'selected' : '';
                echo "<option value='$term' $selected>$term months</option>";
            }
            ?>
        </select>

        <button type="submit" style="margin-top:15px;">Compute Loan</button>
    </form>


    <?php if ($errorMessage): ?>
        <div class="error"><?php echo $errorMessage; ?></div>
    <?php elseif ($monthlyPayment > 0): ?>
        <div class="result">
            <h3>Loan Summary</h3>
            <p><strong>Loan Amount:</strong> ₱<?php echo number_format(num: $loanAmount, decimals: 2); ?></p>
            <p><strong>Loan Term:</strong> <?php echo $loanTerm; ?> months</p>
            <p><strong>Monthly Interest Rate:</strong> <?php echo number_format(num: $monthlyInterestRate * 100, decimals: 2); ?>%</p>
            <p><strong>Payment per Month:</strong> ₱<?php echo number_format(num: $monthlyPayment, decimals: 2); ?></p>
            <p><strong>Total Interest:</strong> ₱<?php echo number_format(num: $totalInterest, decimals: 2); ?></p>
            <p><strong>Total Amount to Pay:</strong> ₱<?php echo number_format(num: $totalPayment, decimals: 2); ?></p>
        </div>
    <?php endif; ?>

    <div class="nav-buttons">
        <a href="index.php"><button type="return" style="margin-top:15px;">back</button></a>
    
    </div>

</div>
</body>
</html>