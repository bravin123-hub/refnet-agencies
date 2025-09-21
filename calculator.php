<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Calculator & Currency Converter</title>
    <style>
        body { font-family: Arial, sans-serif; padding:20px; background:#f4f4f4; }
        .card { background:white; padding:20px; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1); max-width:500px; margin:auto; margin-bottom:20px; }
        input, select { padding:10px; font-size:16px; margin:5px 0; }
        button { padding:10px; margin:2px; font-size:16px; cursor:pointer; border:none; border-radius:5px; background:#ecf0f1; }
        button:hover { background:#bdc3c7; }
        #calc-display { width:100%; font-size:18px; padding:10px; margin-bottom:10px; }
    </style>
</head>
<body>

<div class="card">
    <h2>🧮 Scientific Calculator</h2>
    <input type="text" id="calc-display" disabled>

    <div style="display:grid; grid-template-columns: repeat(5, 1fr); gap:10px; margin-top:10px;">
        <button onclick="clearDisplay()">C</button>
        <button onclick="deleteLast()">⌫</button>
        <button onclick="appendValue('(')">(</button>
        <button onclick="appendValue(')')">)</button>
        <button onclick="appendValue('%')">%</button>

        <button onclick="appendValue('7')">7</button>
        <button onclick="appendValue('8')">8</button>
        <button onclick="appendValue('9')">9</button>
        <button onclick="appendValue('/')">÷</button>
        <button onclick="appendValue('**')">xʸ</button>

        <button onclick="appendValue('4')">4</button>
        <button onclick="appendValue('5')">5</button>
        <button onclick="appendValue('6')">6</button>
        <button onclick="appendValue('*')">×</button>
        <button onclick="appendValue('Math.sqrt(')">√</button>

        <button onclick="appendValue('1')">1</button>
        <button onclick="appendValue('2')">2</button>
        <button onclick="appendValue('3')">3</button>
        <button onclick="appendValue('-')">−</button>
        <button onclick="appendValue('Math.pow(')">x²</button>

        <button onclick="appendValue('0')">0</button>
        <button onclick="appendValue('.')">.</button>
        <button onclick="appendValue('+')">+</button>
        <button onclick="calculateResult()" style="grid-column: span 2; background:#1abc9c; color:white;">=</button>
    </div>
</div>

<div class="card">
    <h2>💱 Currency Converter</h2>
    <input type="number" id="currency-amount" placeholder="Amount">
    <select id="currency-from">
        <option value="USD">USD</option>
        <option value="EUR">EUR</option>
        <option value="GBP">GBP</option>
        <option value="KES">KES</option>
        <option value="JPY">JPY</option>
    </select>
    →
    <select id="currency-to">
        <option value="USD">USD</option>
        <option value="EUR">EUR</option>
        <option value="GBP">GBP</option>
        <option value="KES">KES</option>
        <option value="JPY">JPY</option>
    </select>
    <p id="currency-result" style="margin-top:10px; font-weight:bold;"></p>
</div>

<script>
let display = document.getElementById('calc-display');

// Calculator functions
function appendValue(val){ display.value += val; }
function clearDisplay(){ display.value = ''; }
function deleteLast(){ display.value = display.value.slice(0,-1); }
function calculateResult(){
    try{
        display.value = eval(display.value);
    }catch{
        display.value = "Error";
    }
}

// Currency converter
const amountInput = document.getElementById('currency-amount');
const fromSelect = document.getElementById('currency-from');
const toSelect = document.getElementById('currency-to');
const resultDisplay = document.getElementById('currency-result');

async function convertCurrency(){
    const amount = parseFloat(amountInput.value);
    const from = fromSelect.value;
    const to = toSelect.value;
    if(isNaN(amount)) { resultDisplay.innerText = ''; return; }

    try{
        // Using exchangerate.host free API
        const res = await fetch(`https://113494aa752719a4a3aed284b17e5f01/convert?from=${from}&to=${to}&amount=${amount}`);
        const data = await res.json();
        resultDisplay.innerText = `${amount} ${from} = ${data.result.toFixed(2)} ${to}`;
    }catch(err){
        resultDisplay.innerText = "Conversion failed!";
    }
}

// Convert automatically on input or selection change
amountInput.addEventListener('input', convertCurrency);
fromSelect.addEventListener('change', convertCurrency);
toSelect.addEventListener('change', convertCurrency);
</script>

</body>
</html>
