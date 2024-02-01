<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>AMTIS Electric Bill Calculator</title>
    <link rel="stylesheet" href="CalcStyle.css">
</head>

<body>
<?php

function calculate_electricity_rates(float $voltage, float $current, float $rate, int $hours): array
{
  // Convert rate from sen to RM
  $rate = $rate / 100;

  // Calculate power in watts and divide by 1000 to convert Wh to kWh
  $power = $voltage * $current / 1000;

  $i = 1;
  $totalEnergy = 0;
  $totalCharge = 0;

  while ($i <= 24) {
    // Calculate energy in kWh
    $energy = ($power * $i); 

    // Calculate total charge per hour
    $totalCharge += $energy * $rate;

    $totalEnergy += $energy; // Accumulate total energy

    $i++;
  }

  return [
    'energy' => $totalEnergy,
    'power' => $power,
    'rate' => $rate,
    'total_charge' => $totalCharge,
  ];
}

// Get user input
$voltage = $_POST['voltage'] ?? 0;
$current = $_POST['current'] ?? 0;
$rate = $_POST['rate'] ?? 0;  // Default to 21.8 sen/kWh if not provided
$hours = $_POST['hours'] ?? 1;

// Validate user input (e.g., check for positive numbers)

// Calculate rates
$rates = calculate_electricity_rates($voltage, $current, $rate, $hours);

// Display results
?>

<h1>AMTIS Electric Bill Calculator</h1>
<h3>Calculate:</h3>
    
<form method="post">
    <h4>Voltage, Voltage (V)</h4>
    <input type="number" id="voltage" name="voltage" step="0.01" value="<?php echo $voltage; ?>" required>

    <h4>Current, Ampere (A)</h4>
    <input type="number" id="current" name="current" step="0.01" value="<?php echo $current; ?>" required>

    <h4>Current Rate (sen/kWh)</h4>
    <input type="number" id="rate" name="rate" step="0.01" value="<?php echo $rate; ?>" required>

    <button type="submit">Calculate</button>
</form>

<?php if (isset($rates)): ?>
<div class="results">

<p>POWER: <?php echo number_format($rates['power'], 5); ?> kW</p>
<p>RATE: <?php echo number_format($rates['rate'], 3); ?> RM</p>

<h2>Hourly Results</h2>

<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Hour</th>
      <th>Energy (kWh)</th>
      <th>Total (RM)</th>
    </tr>
  </thead>

  <tbody>
    <?php
    $i = 1;
    while ($i <= 24) {
      // Calculate energy for each hour
      $energy = ($rates['power'] * $i); 
      // Calculate total charge per hour
      $totalCharge = $energy * $rates['rate'];

      echo "<tr>";
      echo "<td>$i</td>";
      echo "<td>$i</td>";
      echo "<td>" . number_format($energy, 5) . "</td>"; // Display energy in kWh
      echo "<td>" . number_format($totalCharge, 2) . "</td>"; // Display total charge for the hour
      echo "</tr>";
      $i++;
    }
    ?>
  </tbody>

</table>
</div>
<?php endif; ?>


</body>
</html>
