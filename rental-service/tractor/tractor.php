<?php
// Example: near the top of tractor.php

$servername = "localhost";
$username = "root";
$password = "";
$database = "krushi-manch";
$port= "3307";


$conn = new mysqli($servername, $username, $password, $database, $port);

if (isset($_POST['return_rent'])) {
    $tractor_id = $_POST['tractor_id'];
    // Update the rent request to completed
    $conn->query("DELETE FROM rent_requests WHERE tractor_id = $tractor_id AND status = 'confirmed'");
}
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


if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'confirm') {
        mysqli_query($conn, "UPDATE rent_requests SET status = 'confirmed' WHERE id = $id");
    } elseif ($action == 'cancel') {
        mysqli_query($conn, "UPDATE rent_requests SET status = 'cancelled' WHERE id = $id");
    }

    header("Location: /krushi-manch/rental-service/tractor/tractor.php");
    exit();
}
?>
<?php
$conn = new mysqli("localhost", "root", "", "krushi-manch", "3307");

// Rent Confirm / Cancel logic
if (isset($_POST['confirm_rent'])) {
    $request_id = $_POST['request_id'];
    $conn->query("UPDATE rent_requests SET status='confirmed' WHERE id=$request_id");
}

if (isset($_POST['cancel_rent'])) {
    $request_id = $_POST['request_id'];
    $conn->query("UPDATE rent_requests SET status='cancelled' WHERE id=$request_id");
}

// Handle Rent Request Form Submission with Dates
if (isset($_POST['send_request'])) {
    $tractor_id = $_POST['tractor_id'];
    $name = $_POST['renter_name'];
    $mobile = $_POST['renter_mobile'];
    $address = $_POST['renter_address'];
    $start_date = $_POST['rent_start_date'];
    $end_date = $_POST['rent_end_date'];

    $stmt = $conn->prepare("INSERT INTO rent_requests (tractor_id, renter_name, renter_mobile, renter_address, rent_start_date, rent_end_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $tractor_id, $name, $mobile, $address, $start_date, $end_date);
    $stmt->execute();
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="shortcut icon" href="/test website/image/K.jpg" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <title>Reantal service</title>
</head>

<body>
    <!-- start navbar -->
    <nav>
        <div class="logo">
            <img src="./tractor-image/Krushi_Manch.png" alt="#">
        </div>
        <input type="checkbox" id="click">
        <label for="click" class="menu-btn">
            <i class="fas fa-bars"></i>
        </label>
        <ul class="text">
            <li><a class="active" href="/krushi-manch/rental-service/index.html">Home</a></li>
           
            <li><a href="./from/index.html"><i class="ri-upload-cloud-2-line">Upload</i></a></li>
        </ul>
    </nav>

    <!-- Start Service Page -->
    <div class="service">
        <?php
        $server = "localhost";
        $Username = "root";
        $password = "";
        $database = "krushi-manch";
        $port= "3307";

        $conn = mysqli_connect($server, $Username, $password, $database, $port);
        if (!$conn) {
            die('Error' . mysqli_connect_error());
        }

        $sql = "SELECT * FROM `trector-rent`";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $vehicleName = htmlspecialchars($row['vehicle-name']);
            $id = $row['Id'];

            // Check for pending rent request
            $checkRequest = mysqli_query($conn, "SELECT * FROM rent_requests WHERE tractor_id = $id AND status = 'pending'");
            $hasPending = mysqli_num_rows($checkRequest) > 0;

            $checkConfirmed = mysqli_query($conn, "SELECT * FROM rent_requests WHERE tractor_id = $id AND status = 'confirmed'");
            $isConfirmed = mysqli_num_rows($checkConfirmed) > 0;
            $requestData = $hasPending ? mysqli_fetch_assoc($checkRequest) : null;
        ?>
            <?php
            $rent_end = isset($requestData['rent_end_date']) ? $requestData['rent_end_date'] : '';
            ?>
            <div class="service-card <?php echo $isConfirmed ? 'locked' : ''; ?>" style="position: relative;">

                <?php if ($isConfirmed): ?>
                    <div class="rented-overlay">
                        <div class="on-rent-message">
                            On Rent
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($isConfirmed): ?>
                    <form method="post" style="pointer-events: auto;">
                        <input type="hidden" name="tractor_id" value="<?php echo $id; ?>">
                        <button type="submit" name="return_rent" class="btn btn-warning">Return</button>
                    </form>
                <?php endif; ?>

                <?php if (!$isConfirmed && $hasPending): ?>
                    <div style="position: absolute; top: 10px; right: 10px; cursor: pointer; z-index: 999;" data-bs-toggle="modal" data-bs-target="#modal_<?php echo $id; ?>">

                        <span style="color: red; font-size: 20px;"><i class="ri-notification-4-line"></i></span>
                    </div>
                <?php endif; ?>

                <!-- Tractor Images -->
                <div class="slider-container">

                    <div class="slides-wrapper">
                        <div class="slide"><img src="./from/<?php echo $row['front']; ?>" alt="<?php echo $vehicleName; ?>"></div>
                        <div class="slide"><img src="./from/<?php echo $row['left']; ?>" alt="<?php echo $vehicleName; ?>"></div>
                        <div class="slide"><img src="./from/<?php echo $row['right']; ?>" alt="<?php echo $vehicleName; ?>"></div>
                        <div class="slide"><img src="./from/<?php echo $row['back']; ?>" alt="<?php echo $vehicleName; ?>"></div>
                    </div>
                </div>

                <h3><?php echo $vehicleName; ?></h3>

                <a href="./tractor details/index.php?id=<?php echo $id; ?>">
                    <button id="detail">More details</button>
                </a>
            </div>

            <!-- Modal for Confirm/Cancel -->
            <?php if ($hasPending): ?>

                <div class="modal fade" id="modal_<?php echo $id; ?>" tabindex="-1" aria-labelledby="modalLabel_<?php echo $id; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel_<?php echo $id; ?>">Rent Request Confirmation</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($requestData['renter_name']); ?></p>
                                <p><strong>Mobile:</strong> <?php echo htmlspecialchars($requestData['renter_mobile']); ?></p>
                                <p><strong>Address:</strong> <?php echo htmlspecialchars($requestData['renter_address']); ?></p>
                                <p><strong>Start Date:</strong> <?php echo htmlspecialchars($requestData['rent_start_date']); ?></p>
                                <p><strong>End Date:</strong> <?php echo htmlspecialchars($requestData['rent_end_date']); ?></p>
                            </div>

                            <div class="modal-footer">
                                <a href="/krushi-manch/rental-service/tractor/tractor.php?id=<?php echo $requestData['id']; ?>&action=confirm" class="btn btn-success">Confirm</a>
                                <a href="/krushi-manch/rental-service/tractor/tractor.php?id=<?php echo $requestData['id']; ?>&action=cancel" class="btn btn-danger">Cancel</a>
                            </div>

                        </div>
                    </div>
                </div>

            <?php endif; ?>

        <?php } ?>
    </div>

    </section>
    <!-- close Service Page -->
</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const navLinks = document.querySelectorAll("nav ul.text li a");
        const checkbox = document.getElementById("click");

        navLinks.forEach(link => {
            link.addEventListener("click", function() {
                checkbox.checked = false; // Uncheck the checkbox to close menu
            });
        });
    });
</script>
<script>
    class Slider {
        constructor(container) {
            this.currentSlide = 0;
            this.container = container;
            this.slidesWrapper = container.querySelector('.slides-wrapper');
            this.slides = container.querySelectorAll('.slide');
            this.interval = null; // Store the interval ID
            this.init();
        }

        init() {
            this.showSlide(this.currentSlide);
            this.startInterval(); // Start automatic sliding
        }

        showSlide(index) {
            const offset = -index * 100; // Calculate the offset for the slide
            this.slidesWrapper.style.transform = `translateX(${offset}%)`; // Apply the transform
        }

        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.slides.length;
            this.showSlide(this.currentSlide);
        }

        startInterval() {
            this.interval = setInterval(() => this.nextSlide(), 1700);
        }
    }

    // Initialize sliders for all card containers
    document.querySelectorAll('.slider-container').forEach(container => {
        new Slider(container);
    });
</script>

<script>
    // Loop through all service cards
    document.querySelectorAll('.service-card').forEach(card => {
        const status = card.getAttribute('data-status'); // "confirmed" or other
        const endDateStr = card.getAttribute('data-end-date'); // format: "YYYY-MM-DD"

        if (status === 'confirmed' && endDateStr) {
            const today = new Date().setHours(0, 0, 0, 0);
            const endDate = new Date(endDateStr).setHours(0, 0, 0, 0);

            if (today <= endDate) {
                card.classList.add('locked'); // Lock the card

                const overlay = document.createElement('div');
                overlay.classList.add('on-rent-overlay');
                overlay.innerHTML = 'On Rent';
                card.appendChild(overlay); // Show the overlay
            } else {
                // Rent expired: unlock the card
                card.classList.remove('locked');
                const existingOverlay = card.querySelector('.on-rent-overlay');
                if (existingOverlay) existingOverlay.remove(); // Remove the overlay
            }
        }
    });
</script>


</html>