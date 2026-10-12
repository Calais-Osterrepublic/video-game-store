<?php
// Part 2:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Error array (carries error messages to be displayed across the program)
    $errors = [];

    // Store Submission Data
    $inventoryCode = trim($_POST['inventoryCode']) ?? "";
    $gameName = trim($_POST['gameName']) ?? "";
    $consoleChoice = trim($_POST['consoleChoice']) ?? "";
    $price = trim($_POST['price']) ?? "";
    $userDocument = $_FILES['userDocument'] ?? "";



    // Validate inventoryCode - display error if empty or does not match the required format
    if (empty($inventoryCode)) {
        $errors["inventoryCode"] = "Inventory code is required.";
    } elseif (!preg_match("/^GAME-\d{4}$/", $inventoryCode)) {
        $errors["inventoryCode"] = "Inventory code must be in the format GAME-1234.";
    }

    // Validate gameName - display error if empty
    if (empty($gameName)) {
        $errors["gameName"] = "Game name is required.";
    }

    // Validate consoleChoice - display error if empty
    if ($consoleChoice === "") {
        $errors["consoleChoice"] = "Console choice is required.";
    }
    // Validate price - display error if empty, not numeric, or less than or equal to zero
    if (empty($price)) {
        $errors["price"] = "Price is required.";
    } elseif (!is_numeric($price) || $price <= 0) {
        $errors["price"] = "Price must be a number greater than zero.";
    }

    // Validate userDocument - display error if empty
    if (empty($userDocument['name'])) {
        $errors["userDocument"] = "Game image is required.";
    }

    // If $errors isn't empty, fall through to the HTML form;
    // If $errors is empty, process the form data (e.g., save to database, save image, etc.)
    if (empty($errors)) {
        // Create a unique image filename using milliseconds so one upload does not replace another.
        $filename = pathinfo($userDocument['name'], PATHINFO_FILENAME) . '-' . round(microtime(true) * 1000) . '.' . pathinfo($userDocument['name'], PATHINFO_EXTENSION);
        
        // Move the uploaded image into the uploads folder.
        $destination = __DIR__ . '/uploads/' . $filename;

        // Move the file out of PHP's temporary upload location
        move_uploaded_file(
            $userDocument["tmp_name"],
            $destination
        );

        // Open CSV file
        $csvFile = fopen(__DIR__ . '\games\games.csv', 'a');

        // If file doesn't exist, implement die error
        if (!$csvFile) {
            die("Error: Unable to open the CSV file for writing.");
        }
        // Write one CSV row containing the inventory code, game name, console, price, and saved image filename.
        fputcsv($csvFile, [$inventoryCode, $gameName, $consoleChoice, $price, $filename]);

        // Close the CSV file.
        fclose($csvFile);

    }

}

?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <title>Video Game Store</title>
</head>

<body class="bg-dark">
    <?php include 'includes/navigation.php'; ?>
    <section class="container mt-4 border p-3 bg-primary-subtle text-primary-emphasis">
        <h1>Event Registration Form</h1>
        <form action="index.php " method="POST" enctype="multipart/form-data">
            <!-- Inventory Code -->
            <label for="inventoryCode">Inventory Code:</label>
            <input type="text" name="inventoryCode" id="inventoryCode"
                value="<?= isset($inventoryCode) ? htmlspecialchars($inventoryCode) : '' ?>">
            <!-- display error if inventoryCode shows up in the $errors array -->
            <?php if (!empty($errors["inventoryCode"])): ?>
                <!-- If inventoryCode has an error, display the input field with the submitted value and the error message -->
                <div class="alert alert-danger mt-1 mb-4"><?= $errors["inventoryCode"] ?></div>
            <?php endif; ?>
            <p></p>

            <!-- Game Name -->
            <label for="gameName">Game Name:</label>
            <input type="text" name="gameName" id="gameName"
                value="<?= isset($gameName) ? htmlspecialchars($gameName) : '' ?>">
            <!-- display error if gameName shows up in the $errors array -->
            <?php if (!empty($errors["gameName"])): ?>
                <!-- If gameName has an error, display the input field with the submitted value and the error message -->
                <div class="alert alert-danger mt-1 mb-4"><?= $errors["gameName"] ?></div>
            <?php endif; ?>
            <p></p>

            <!-- Console Choice -->
            <label for="consoleChoice">Select console release:</label>
            <select name="consoleChoice" id="consoleChoice">
                <option value="">--Select a console--</option>
                <!-- Since my select options were hardcoded, I used an echo shorthand to preselect the console option, if there was one to work with -->
                <!-- Gen Eight (Wikipedia) -->
                <option value="ps4" <?= (isset($consoleChoice) && $consoleChoice === "ps4") ? "selected" : "" ?>>
                    Playstation 4 - Sony</option>
                <option value="xboxOne" <?= (isset($consoleChoice) && $consoleChoice === "xboxOne") ? "selected" : "" ?>>
                    Xbox One - Microsoft</option>
                <option value="switch" <?= (isset($consoleChoice) && $consoleChoice === "switch") ? "selected" : "" ?>>
                    Nintendo Switch</option>
                <!-- Gen Nine (Wikipedia) -->
                <option value="ps5" <?= (isset($consoleChoice) && $consoleChoice === "ps5") ? "selected" : "" ?>>
                    Playstation 5 - Sony</option>
                <option value="xboxSeriesX" <?= (isset($consoleChoice) && $consoleChoice === "xboxSeriesX") ? "selected" : "" ?>>Xbox Series X - Microsoft</option>
                <option value="switch2" <?= (isset($consoleChoice) && $consoleChoice === "switch2") ? "selected" : "" ?>>
                    Nintendo Switch 2</option>
            </select>
            <!-- display error if consoleChoice shows up in the $errors array -->
            <?php if (!empty($errors["consoleChoice"])): ?>
                <div class="alert alert-danger mt-1 mb-4"><?= $errors["consoleChoice"] ?></div>
            <?php endif; ?>
            <p></p>

            <!-- Price -->
            <label for="price">Price:</label>
            <input type="number" name="price" id="price" value="<?= isset($price) ? htmlspecialchars($price) : '' ?>">
            <!-- display error if price shows up in the $errors array -->
            <?php if (!empty($errors["price"])): ?>
                <div class="alert alert-danger mt-1 mb-4"><?= $errors["price"] ?></div>
            <?php endif; ?>
            <p></p>

            <!-- User Document -->
            <label for="userDocument">Game Image:</label>
            <input type="file" id="userDocument" name="userDocument" accept=".jpg, .jpeg, .png">
            <!-- display error if userDocument shows up in the $errors array -->
            <?php if (!empty($errors["userDocument"])): ?>
                <div class="alert alert-danger mt-1 mb-4"><?= $errors["userDocument"] ?></div>
            <?php endif; ?>
            <p></p>
            <input type="submit" value="Register">
        </form>
    </section>
</body>

</html>