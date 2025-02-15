<?php
// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crud_app";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create operation: Insert data into the database
if (isset($_POST['create'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $sql = "INSERT INTO users (name, age) VALUES ('$name', '$age')";
    $conn->query($sql);
}

// Read operation: Fetch data from the database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);


if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $sql = "UPDATE users SET name='$name', age='$age' WHERE id=$id";
    
}

// Delete operation: Remove data from the database
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM users WHERE id=$id";
    $conn->query($sql);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP CRUD with Chart.js</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>CRUD Operations</h2>
    <form method="post">
        Name: <input type="text" name="name" required>
        Age: <input type="number" name="age" required>
        <button type="submit" name="create">Create</button>
    </form>
    
    <h2>User List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['age']; ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
                    <input type="number" name="age" value="<?php echo $row['age']; ?>" required>
                    <button type="submit" name="update">Update</button>
                </form>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="delete">Delete</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>

    <!-- Data Visualization -->
    <h2>Age Distribution Chart</h2>
    <canvas id="ageChart"></canvas>
    <script>
        var ctx = document.getElementById('ageChart').getContext('2d');
        var chartData = {
            labels: [<?php $result->data_seek(0); while ($row = $result->fetch_assoc()) { echo "'" . $row['name'] . "',"; } ?>],
            datasets: [{
                label: 'Age',
                data: [<?php $result->data_seek(0); while ($row = $result->fetch_assoc()) { echo $row['age'] . ","; } ?>],
                backgroundColor: 'rgba(54, 162, 235, 0.5)'
            }]
        };
        new Chart(ctx, { type: 'bar', data: chartData });
    </script>
</body>
</html>
<?php
$conn->close();
?>