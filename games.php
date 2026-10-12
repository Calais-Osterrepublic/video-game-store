<?php
/*
games.php has one job: read all records from games/games.csv and display them in a table. It should not process the submitted form.

The table must include:

    Image thumbnail
    Inventory code
    Video game name
    Console
    Price formatted as currency with two decimal places

Escape CSV values before displaying them in HTML. Give every thumbnail a consistent size, and include a clear link back to index.php so another game can be added.

If the CSV file does not contain any records yet, display a friendly message instead of an empty or broken table.
*/
$csvFile = fopen(__DIR__ . '\games\games.csv', 'r');

// If the file does exist and has data, run through the CSV file and store each row in an array. 
if ($csvFile && !feof($csvFile)) {
    $games = [];
    while (($row = fgetcsv($csvFile)) !== false) {
        $games[] = $row;
    }
    fclose($csvFile);
} 
// If the file does not exist or is empty, set $games to an empty array. The message will be handled in the HTML body.
else {
    $games = [];
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
    <title>Video Games Store - Games</title>
</head>
<body class="bg-dark">
    <?php include 'includes/navigation.php'; ?>
    <?php if (empty($games)): ?>
        <div class="container alert alert-info mt-4 p-3" role="alert">
            <h1>Whoops!</h1>
            <p>No games available yet.</p>
            <a class="btn btn-primary" href="index.php">Return to Video Game Store</a>
        </div>
    <?php else: ?>
        <div class="container mt-4">
            <h1 class="mb-4">Game Listings</h1>
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Thumbnail</th>
                        <th scope="col">Inventory Code</th>
                        <th scope="col">Game Name</th>
                        <th scope="col">Console</th>
                        <th scope="col">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($games as $game): ?>
                        <tr>
                            <td><img 
                                src="<?= htmlspecialchars("uploads/" . $game[4]) ?>" 
                                alt="<?= htmlspecialchars($game[1]) ?> 
                                thumbnail" style="width: 100px; height: auto;">
                            </td>
                            <td><?= htmlspecialchars($game[0]) ?></td>
                            <td><?= htmlspecialchars($game[1]) ?></td>
                            <td><?= htmlspecialchars($game[2]) ?></td>
                            <td>$<?= number_format((float)$game[3], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a class="btn btn-primary" href="index.php">Return to Video Game Store</a>
        </div>
    <?php endif; ?>
</body>
</html>