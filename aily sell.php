<?php
	include('conn.php');

	// Retrieve the list of products
	$sql = "SELECT * FROM product";
	$result = $conn->query($sql);

	// Display the form
	echo "<form method='post'>";
	echo "<label for='date'>Date:</label>";
	echo "<input type='date' name='date' id='date'>";
	echo "<br>";
	echo "<label for='part_number'>Part number:</label>";
	echo "<select name='product' id='part_number'>";
	while ($row = $result->fetch_assoc()) {
		echo "<option value='" . $row['part_number'] . "'>" . $row['description'] . "</option>";
	}
	echo "</select>";
	echo "<br>";
	echo "<label for='quantity'>Quantity:</label>";
	echo "<input type='number' name='quantity' id='quantity'>";
	echo "<br>";
	echo "<label for='price'>Selling Price:</label>";
	echo "<input type='number' name='price' id='price'>";
	echo "<br>";
	echo "<input type='submit' value='Submit'>";
	echo "</form>";

	// When the form is submitted
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		// Retrieve the values
		$date = $_POST['date'];
		$product = $_POST['part_number'];
		$quantity = $_POST['quantity'];
		$price = $_POST['price'];

		// Check to ensure that the product and quantity sold are valid values
		$sql = "SELECT * FROM product WHERE part_number = $part_number";
		$result = $conn->query($sql);
		if ($result->num_rows == 1) {
			$row = $result->fetch_assoc();
			$current_quantity = $row['quantity'];
			if ($quantity > $current_quantity) {
				echo "Error: Not enough products in stock";
				exit;
			}
		} else {
			echo "Error: Invalid product";
			exit;
		}

		// Calculate the total revenue
		$revenue = $quantity * $price;

		// Update the product table
		$new_quantity = $current_quantity - $quantity;
		$sql = "UPDATE product SET quantity = $new_quantity WHERE part_number = $part_number";
		if ($conn->query($sql) === FALSE) {
			echo "Error updating record: " . $conn->error;
			exit;
		}

		// Insert a new row into the sells table
		$sql = "INSERT INTO sells (date, part_number, quantity, sells_price) VALUES ('$date', $product_id, $quantity, $price)";
		// Execute SQL statement
	if ($conn->query($sql) === TRUE) {
		echo "New sell record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}

	// Close database connection
?>
