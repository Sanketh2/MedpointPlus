<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="icons/main.ico">
    <title>MedPoint Plus</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="content-container">
        
        <h3>Welcome to MedPoint Plus</h3>
        <p>Discover seamless appointment management with MedPoint Plus. Designed to enhance your healthcare experience, our intuitive platform empowers you to schedule appointments effortlessly, ensuring timely access to quality care.</p>
        <h4>Key Features:</h4>
        <ul>
            <li><strong>Effortless Scheduling:</strong> Book appointments with ease, anytime and anywhere.</li>
            <li><strong>Patient-Centric Approach:</strong> Prioritize your health with personalized scheduling options.</li>
            <li><strong>Enhanced Accessibility:</strong> Access your medical appointments securely from any device.</li>
        </ul>
        <p>Join thousands of users who trust MedPoint Plus for a smarter, more efficient healthcare solution. Experience the future of medical appointment systems today.</p>
        
    </div>

    <div class="flex-container">
        <div class="text-section">
            <h3>Why Choose MedPoint Plus?</h3>
            <ul>
                <li><strong>Experienced Healthcare Professionals:</strong> Our team comprises skilled doctors, physicians, and nurses dedicated to providing top-notch medical care.</li>
                <li><strong>Comprehensive Care:</strong> We have successfully treated over 100,000 patients, ensuring each receives personalized attention and effective treatment.</li>
                <li><strong>Advanced Technology:</strong> Utilizing state-of-the-art facilities and technology, we deliver precise diagnoses and effective medical solutions.</li>
                <li><strong>Patient Satisfaction:</strong> With a focus on patient comfort and satisfaction, we strive to exceed your expectations in healthcare delivery.</li>
            </ul>
        </div>

        <div class="image-section">
            <!-- Static Image Without Background -->
            <img src="images/doctor_image.png" alt="Doctor Image">
        </div>
    </div>

    <div class="image-gallery">
    <div class="image-container">
        <img id="galleryImage" src="images/image1.jpg" alt="Image 1">
    </div>
</div>


    <!-- Your PHP redirection code -->
    <?php
        if(isset($_POST["patientbtn"])){
            header("Location: patient.php");
        }
        if(isset($_POST["doctorbtn"])){
            header("Location: doctor.php");
        }
        if(isset($_POST["adminbtn"])){
            header("Location: admin.php");
        }
    ?>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
    const images = [
        'images/image1.jpg',
        'images/image2.jpg',
        'images/image3.jpg',
        'images/image4.jpg',
        'images/image5.jpg',
        // Add more image paths as needed
    ];
    let currentImageIndex = 0;
    const galleryImage = document.getElementById('galleryImage');

    setInterval(() => {
        const nextImageIndex = (currentImageIndex + 1) % images.length;
        
        // Fade out current image
        galleryImage.style.opacity = 0;

        // Preload next image
        const nextImage = new Image();
        nextImage.src = images[nextImageIndex];

        // After a short delay, switch to next image and fade in
        setTimeout(() => {
            galleryImage.src = nextImage.src;
            galleryImage.style.opacity = 1;
            currentImageIndex = nextImageIndex;
        }, 500); // Adjust the delay (in milliseconds) to match the transition duration
    }, 3000); // Adjust the interval (in milliseconds) for transition time
});

</script>
</body>
</html>
