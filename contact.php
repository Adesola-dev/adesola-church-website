<?php
include 'includes/header.php';
include 'includes/church-navbar.php';
include 'includes/db.php';

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $user_message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($phone) || empty($user_message)) {

        $message = "Please fill in all required fields.";
        $message_type = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $message_type = "error";

    } else {

        /*
         * Save enquiry into database
         *
         * Make sure your messages table exists.
         */

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO messages (name, email, phone, subject, message)
             VALUES (?, ?, ?, ?, ?)"
        );

        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "sssss",
                $name,
                $email,
                $phone,
                $subject,
                $user_message
            );

            if (mysqli_stmt_execute($stmt)) {

                $message = "Thank you for contacting us. Your message has been received.";
                $message_type = "success";

            } else {

                $message = "Sorry, your message could not be sent. Please try again.";
                $message_type = "error";
            }

            mysqli_stmt_close($stmt);

        } else {

            $message = "Unable to process your message at the moment.";
            $message_type = "error";
        }
    }
}
?>

<!-- CONTACT HERO -->
<section class="page-hero church-contact-hero">

    <div class="container">

        <span class="hero-label">GET IN TOUCH</span>

        <h1>Contact Us</h1>

        <p>
            We would love to hear from you. Reach out to us for enquiries,
            prayer, counselling, or more information about our church.
        </p>

    </div>

</section>


<!-- CONTACT SECTION -->
<section class="church-contact-section">

    <div class="container">

        <div class="church-contact-grid">


            <!-- CONTACT INFORMATION -->
            <div class="church-contact-info">

                <span class="section-label">
                    CONTACT INFORMATION
                </span>

                <h2>
                    We Are Here For You
                </h2>

                <p>
                    Whether you have a question, need spiritual guidance,
                    would like to schedule an appointment, or simply want
                    to connect with us, our team is available to assist you.
                </p>


                <!-- ADDRESS -->
                <div class="contact-info-item">

                    <div class="contact-icon">
                        📍
                    </div>

                    <div>
                        <h3>Our Location</h3>

                        <p>
                            Plot 6-10, Amusa Salvage Street,<br>
                            Ile-Ise Bus Stop,<br>
                            Along Agbado Crossing Road,<br>
                            Ogun State, Nigeria.
                        </p>
                    </div>

                </div>


                <!-- PHONE -->
                <div class="contact-info-item">

                    <div class="contact-icon">
                        📞
                    </div>

                    <div>

                        <h3>Phone</h3>

                        <p>
                            07031093290<br>
                            08097036144
                        </p>

                    </div>

                </div>


                <!-- EMAIL -->
                <div class="contact-info-item">

                    <div class="contact-icon">
                        ✉️
                    </div>

                    <div>

                        <h3>Email</h3>

                        <p>
                            bamideleolusegun30@gmail.com
                        </p>

                    </div>

                </div>


                <!-- WHATSAPP -->
                <div class="contact-info-item">

                    <div class="contact-icon">
                        💬
                    </div>

                    <div>

                        <h3>WhatsApp</h3>

                        <p>
                            +234 703 109 3290
                        </p>

                    </div>

                </div>


                <!-- APPOINTMENT BUTTON -->
                <a href="appointment.php" class="contact-appointment-btn">
                    Book an Appointment
                </a>

            </div>


            <!-- CONTACT FORM -->
            <div class="church-contact-form-wrapper">

                <span class="section-label">
                    SEND US A MESSAGE
                </span>

                <h2>
                    Get In Touch
                </h2>

                <p>
                    Fill out the form below and we will get back to you.
                </p>


                <?php if (!empty($message)): ?>

                    <div class="contact-message <?php echo $message_type; ?>">

                        <?php echo htmlspecialchars($message); ?>

                    </div>

                <?php endif; ?>


                <form method="POST" action="contact.php">


                    <!-- NAME -->
                    <div class="form-group">

                        <label for="name">
                            Full Name *
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            placeholder="Enter your full name"
                            required
                        >

                    </div>


                    <!-- EMAIL -->
                    <div class="form-group">

                        <label for="email">
                            Email Address *
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Enter your email address"
                            required
                        >

                    </div>


                    <!-- PHONE -->
                    <div class="form-group">

                        <label for="phone">
                            Phone Number *
                        </label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            placeholder="Enter your phone number"
                            required
                        >

                    </div>


                    <!-- SUBJECT -->
                    <div class="form-group">

                        <label for="subject">
                            Subject
                        </label>

                        <input
                            type="text"
                            id="subject"
                            name="subject"
                            placeholder="What would you like to talk about?"
                        >

                    </div>


                    <!-- MESSAGE -->
                    <div class="form-group">

                        <label for="message">
                            Message *
                        </label>

                        <textarea
                            id="message"
                            name="message"
                            rows="6"
                            placeholder="Write your message here..."
                            required
                        ></textarea>

                    </div>


                    <!-- SUBMIT -->
                    <button
                        type="submit"
                        class="contact-submit-btn"
                    >
                        Send Message
                    </button>

                </form>

            </div>

        </div>

    </div>

</section>


<!-- MAP / LOCATION -->
<section class="church-location-section">

    <div class="container">

        <div class="section-heading center">

            <span class="section-label">
                FIND US
            </span>

            <h2>
                Visit Us
            </h2>

            <p>
                We look forward to welcoming you.
            </p>

        </div>


        <div class="location-box">

            <div class="location-placeholder">

                <div class="location-icon">
                    📍
                </div>

                <h3>
                    Olusegun Church
                </h3>

                <p>
                    Plot 6-10, Amusa Salvage Street,
                    Ile-Ise Bus Stop,
                    Along Agbado Crossing Road,
                    Ogun State, Nigeria.
                </p>

                <a
                    href="https://www.google.com/maps/search/?api=1&query=Plot+6-10+Amusa+Salvage+Street+Ile-Ise+Bus+Stop+Ogun+State+Nigeria"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="map-button"
                >
                    Get Directions
                </a>

            </div>

        </div>

    </div>

</section>


<!-- CTA -->
<section class="church-contact-cta">

    <div class="container">

        <div class="cta-content">

            <span class="section-label">
                NEED PERSONAL ATTENTION?
            </span>

            <h2>
                Book an Appointment
            </h2>

            <p>
                Schedule a time to meet with us for pastoral counselling,
                prayer, spiritual guidance, ministry meetings, or general enquiries.
            </p>

            <a href="appointment.php" class="cta-button">
                Book an Appointment
            </a>

        </div>

    </div>

</section>


<style>

/* ================================
   CONTACT HERO
================================ */

.church-contact-hero {
    background:
        linear-gradient(
            rgba(23, 26, 99, 0.90),
            rgba(15, 18, 74, 0.94)
        ),
        url("assets/images/ministry.jpg")
        center center / cover no-repeat;

    padding: 110px 20px;
    color: #fff;
    text-align: center;
}

.hero-label,
.section-label {
    display: inline-block;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 2px;
    color: #D9A400;
    margin-bottom: 12px;
}

.church-contact-hero h1 {
    font-size: 48px;
    margin-bottom: 18px;
    color: #FFFFFF;
}

.church-contact-hero p {
    max-width: 700px;
    margin: auto;
    line-height: 1.8;
    font-size: 17px;
}


/* ================================
   CONTACT SECTION
================================ */

.church-contact-section {
    padding: 90px 20px;
    background: #fff;
}

.church-contact-grid {
    max-width: 1150px;
    margin: auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 70px;
}

.church-contact-info h2,
.church-contact-form-wrapper h2 {
    font-size: 36px;
    color: #171A63;
    margin-bottom: 18px;
}

.church-contact-info > p,
.church-contact-form-wrapper > p {
    color: #666;
    line-height: 1.8;
    margin-bottom: 35px;
}


/* CONTACT ITEMS */

.contact-info-item {
    display: flex;
    align-items: flex-start;
    gap: 18px;
    margin-bottom: 28px;
}

.contact-icon {
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 50%;
    background: #F5F6FC;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
}

.contact-info-item h3 {
    margin: 0 0 6px;
    color: #171A63;
    font-size: 17px;
}

.contact-info-item p {
    margin: 0;
    color: #666;
    line-height: 1.7;
}


/* APPOINTMENT BUTTON */

.contact-appointment-btn {
    display: inline-block;
    margin-top: 10px;
    padding: 14px 24px;
    background: #D9A400;
    color: #111;
    text-decoration: none;
    font-weight: 700;
    border-radius: 5px;
}


/* ================================
   FORM
================================ */

.church-contact-form-wrapper {
    background: #FAFAFD;
    padding: 40px;
    border-radius: 10px;
    border: 1px solid #E5E5E5;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 14px 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-family: inherit;
    font-size: 15px;
    box-sizing: border-box;
    outline: none;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #171A63;
}

.form-group textarea {
    resize: vertical;
}

.contact-submit-btn {
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 5px;
    background: #171A63;
    color: #fff;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
}

.contact-submit-btn:hover {
    background: #0F124A;
}


/* SUCCESS / ERROR */

.contact-message {
    padding: 14px 16px;
    margin-bottom: 25px;
    border-radius: 5px;
    font-weight: 600;
}

.contact-message.success {
    background: #e8f7ed;
    color: #176b36;
}

.contact-message.error {
    background: #fdeaea;
    color: #a12626;
}


/* ================================
   LOCATION
================================ */

.church-location-section {
    padding: 80px 20px;
    background: #F5F6FC;
}

.section-heading.center {
    text-align: center;
    margin-bottom: 40px;
}

.section-heading h2 {
    color: #171A63;
    font-size: 36px;
    margin: 5px 0 10px;
}

.section-heading p {
    color: #666;
}

.location-box {
    max-width: 1000px;
    margin: auto;
    min-height: 330px;
    border-radius: 10px;
    overflow: hidden;
    background: #171A63;
}

.location-placeholder {
    min-height: 330px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 40px;
    box-sizing: border-box;
    color: #fff;
}

.location-icon {
    font-size: 42px;
    margin-bottom: 12px;
}

.location-placeholder h3 {
    font-size: 28px;
    margin: 5px 0 12px;
}

.location-placeholder p {
    max-width: 600px;
    line-height: 1.8;
    color: #eee;
}

.map-button {
    display: inline-block;
    margin-top: 15px;
    padding: 13px 22px;
    background: #D9A400;
    color: #111;
    text-decoration: none;
    border-radius: 5px;
    font-weight: 700;
}


/* ================================
   CTA
================================ */

.church-contact-cta {
    padding: 90px 20px;
    background:
        linear-gradient(
            rgba(23, 26, 99, 0.96),
            rgba(15, 18, 74, 0.96)
        );
    color: #fff;
    text-align: center;
}

.cta-content {
    max-width: 750px;
    margin: auto;
}

.cta-content h2 {
    font-size: 40px;
    margin: 8px 0 15px;
}

.cta-content p {
    line-height: 1.8;
    color: #eee;
    margin-bottom: 30px;
}

.cta-button {
    display: inline-block;
    padding: 15px 28px;
    background: #D9A400;
    color: #111;
    text-decoration: none;
    font-weight: 700;
    border-radius: 5px;
}


/* ================================
   MOBILE
================================ */

@media (max-width: 768px) {

    .church-contact-hero {
        padding: 80px 20px;
    }

    .church-contact-hero h1 {
        font-size: 36px;
    }

    .church-contact-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }

    .church-contact-info h2,
    .church-contact-form-wrapper h2 {
        font-size: 30px;
    }

    .church-contact-form-wrapper {
        padding: 25px;
    }

    .section-heading h2 {
        font-size: 30px;
    }

    .cta-content h2 {
        font-size: 32px;
    }

}

</style>


<?php
include 'includes/footer.php';
?>