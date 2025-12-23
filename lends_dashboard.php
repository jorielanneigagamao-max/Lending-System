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
        body {
            font-family: Arial, sans-serif;
            background: #000000ff;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 70%;
            margin: 50px auto;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
        }

        .card {
            width: 500px;
            background: white;
            border-radius: 25px;
            transition: 0.3s;
            text-align: left;
            padding: 30px 25px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card h2 {
            text-align: center;
            margin-bottom: 20px;
            color: rgba(67, 0, 252, 1);
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input, select {
            width: 95%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: rgba(67, 0, 252, 1);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            border: 2px solid rgba(67, 0, 252, 1);
            margin-top: 15px;
            cursor: pointer;
        }

        .result, .error {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
        }

        .result {
            background: #f0f0f0;
        }

        .error {
            background: #fdd;
            color: #900;
        }

        h1.page-title {
            margin-top: 30px;
            margin-bottom: 50px;
            color: white;
        }
    </style>
</head>
<body>

<h1 class="page-title">Loan Dashboard</h1>

<div class="container">
    <div class="card">
        <h2>Lending Loan System</h2>
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

            <button class="btn" type="submit">Compute Loan</button>
            <button class="btn" type="button" onclick="window.location.href='index.php'">Back</button>
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
    </div>
</div>

</body>
</html>
