<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "krushi-manch";
$port= "3307";

$conn = new mysqli($servername, $username, $password, $database, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    echo "No Plough ID provided.";
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM `Plough-rent` WHERE id = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    echo "Plough not found.";
    exit;
}

$row = mysqli_fetch_assoc($result);
$vehicleName = htmlspecialchars($row['vehicle-name']);
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "krushi-manch";
$port= "3307";
$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle rent request form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_request'])) {
    $renter_name = $_POST['renter_name'];
    $renter_mobile = $_POST['renter_mobile'];
    $renter_address = $_POST['renter_address'];
    $plough_id = $_POST['tractor_id'];
    $rent_start_date = $_POST['rent_start_date'];
    $rent_end_date = $_POST['rent_end_date'];

    $stmt = $conn->prepare("INSERT INTO rent_requests_p (plough_id, renter_name, renter_mobile, renter_address, rent_start_date, rent_end_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $plough_id, $renter_name, $renter_mobile, $renter_address, $rent_start_date, $rent_end_date);
    $stmt->execute();
    $stmt->close();

    echo '<script>
            Swal.fire({
                title: "Rent Request Sent Successfully!",
                icon: "success"
            });
          </script>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="shortcut icon" href="/test website/image/K.jpg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Rental Details - <?php echo $vehicleName; ?></title>
</head>

<body>
    <!-- Navbar -->
    <nav class="krushi-detail-nav">
        <div class="krushi-detail-nav-content">
            <a href="/krushi-manch/welcome.php" class="krushi-detail-nav-home">
                <span style="font-size: 20px;">🏠</span> Home
            </a>
            <span class="krushi-detail-nav-title">Plough Details</span>
        </div>
    </nav>
    <style>
    .krushi-detail-nav {
        background: linear-gradient(135deg, #00796B 0%, #00897B 100%);
        padding: 15px 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    .krushi-detail-nav-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        max-width: 1200px;
        margin: 0 auto;
    }
    .krushi-detail-nav-home {
        color: white;
        text-decoration: none;
        font-size: 18px;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 6px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .krushi-detail-nav-home:hover {
        background-color: rgba(255, 255, 255, 0.2);
        transform: translateX(2px);
    }
    .krushi-detail-nav-title {
        color: white;
        font-size: 16px;
        font-weight: 500;
    }
    </style>

    <!-- Tractor Detail Section -->
    <div class="tractor">
        <div class="slider-container">
            <div class="slides-wrapper">
                <div class="slide"><img src="../from/<?php echo $row['front']; ?>" alt="<?php echo $row['vehicle-name']; ?>"></div>
                <div class="slide"><img src="../from/<?php echo $row['left']; ?>" alt="<?php echo $row['vehicle-name']; ?>"></div>
                <div class="slide"><img src="../from/<?php echo $row['right']; ?>" alt="<?php echo $row['vehicle-name']; ?>"></div>
                <div class="slide"><img src="../from/<?php echo $row['back']; ?>" alt="<?php echo $row['vehicle-name']; ?>"></div>
            </div>
        </div>

        <div class="text">
            <h3><?php echo $row['vehicle-name']; ?></h3>
            <p><b>Farmer Name:</b> <?php echo $row['name']; ?></p>
            <p><b>Mobile No:</b> <?php echo $row['number']; ?></p>
            <button type="button" data-bs-toggle="modal" data-bs-target="#rentModal<?php echo $row['Id']; ?>">
                Get On Rent
            </button>
        </div>
    </div>

    <!-- Optional: Specification Table (can customize or hide) -->
     <section id="detail">
    <div class="table">
        <h2><?php echo $vehicleName; ?> Specifications</h2>
        <table>
            <tr>
                <th colspan="2">General Info</th>
            </tr>
            <tr>
                <td>Power</td>
                <td><?php echo $row['power']; ?></td>
            </tr>
            <tr>
                <td>Type</td>
                <td><?php echo $row['type']; ?></td>
            </tr>
            <tr>
                <td>Rent Price/Day</td>
                <td>₹<?php echo $row['rent_price']; ?></td>
            </tr>
            <tr>
                <td>Rent Days Available</td>
                <td><?php echo $row['rent_days']; ?> Days</td>
            </tr>
            <tr>
                <td>Location</td>
                <td><?php echo $row['location']; ?></td>
            </tr>
        </table>
    </div>
    </section>
    <!-- Back Button -->
    <div style="text-align:center; margin: 20px;">
        <a href="/Krushi-Manch/rental-service/Plough/Plough.php">
            <button style="padding: 10px 20px;">← Back to</button>
        </a>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="rentModal<?php echo $row['Id']; ?>" tabindex="-1" aria-labelledby="rentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rentModalLabel">Renter Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="tractor_id" value="<?php echo $row['Id']; ?>">

                    <div class="mb-3">
                        <label class="form-label">Name:</label>
                        <input type="text" class="form-control" name="renter_name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mobile:</label>
                        <input type="number" class="form-control" name="renter_mobile" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address:</label>
                        <input type="text" class="form-control" name="renter_address" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rent Start Date:</label>
                        <input type="date" class="form-control" name="rent_start_date" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rent End Date:</label>
                        <input type="date" class="form-control" name="rent_end_date" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" name="send_request" class="btn btn-success">Send Rent Request</button>
                </div>
            </form>

        </div>
    </div>

</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const navLinks = document.querySelectorAll("nav ul.text li a");
        const checkbox = document.getElementById("click");
        navLinks.forEach(link => {
            link.addEventListener("click", function() {
                checkbox.checked = false;
            });
        });
    });

    class Slider {
        constructor(container) {
            this.currentSlide = 0;
            this.container = container;
            this.slidesWrapper = container.querySelector('.slides-wrapper');
            this.slides = container.querySelectorAll('.slide');
            this.interval = null;
            this.init();
        }

        init() {
            this.showSlide(this.currentSlide);
            this.startInterval();
        }

        showSlide(index) {
            const offset = -index * 100;
            this.slidesWrapper.style.transform = `translateX(${offset}%)`;
        }

        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.slides.length;
            this.showSlide(this.currentSlide);
        }

        startInterval() {
            this.interval = setInterval(() => this.nextSlide(), 3000);
        }
    }

    document.querySelectorAll('.slider-container').forEach(container => {
        new Slider(container);
    });
</script>

<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        const start = new Date(this.rent_start_date.value);
        const end = new Date(this.rent_end_date.value);

        if (start > end) {
            alert("End date cannot be before start date.");
            e.preventDefault();
        }
    });
</script>

</html>