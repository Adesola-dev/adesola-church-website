<?php

include "includes/header.php";
include "includes/navbar.php";
include "includes/db.php";

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $appointment_type = trim($_POST["appointment_type"]);
    $appointment_date = trim($_POST["appointment_date"]);
    $appointment_time = trim($_POST["appointment_time"]);
    $message = trim($_POST["message"]);

    if (
        empty($name) ||
        empty($email) ||
        empty($phone) ||
        empty($appointment_type) ||
        empty($appointment_date) ||
        empty($appointment_time)
    ) {

        $error = "Please fill in all required fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    } else {

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO appointments
            (name, email, phone, appointment_type, appointment_date, appointment_time, message)
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssssss",
            $name,
            $email,
            $phone,
            $appointment_type,
            $appointment_date,
            $appointment_time,
            $message
        );

        if (mysqli_stmt_execute($stmt)) {

            $success = "Your appointment request has been submitted successfully. We will contact you shortly.";

        } else {

            $error = "Something went wrong. Please try again.";

        }

        mysqli_stmt_close($stmt);
    }
}

?>

<!-- =========================================================
     APPOINTMENT HERO
========================================================= -->

<section class="appointment-hero">

    <div class="appointment-hero-overlay"></div>

    <div class="appointment-hero-content">

        <div class="appointment-label">
            CHURCH & MINISTRY
        </div>

        <h1>
            Book an <span>Appointment</span>
        </h1>

        <p>
            Schedule a meeting with our ministry.
            We are available to listen, pray with you
            and provide spiritual guidance.
        </p>

    </div>

</section>


<!-- =========================================================
     APPOINTMENT FORM
========================================================= -->

<section class="appointment-section">

    <div class="container">

        <div class="appointment-layout">

            <!-- LEFT INFORMATION -->

            <div class="appointment-info">

                <div class="church-section-label">
                    CONNECT WITH US
                </div>

                <h2>
                    We Are Here
                    <span>For You</span>
                </h2>

                <p>
                    If you would like to meet with the ministry,
                    need spiritual guidance, counselling, prayer
                    or have an important matter to discuss,
                    you can request an appointment using the form.
                </p>

                <div class="appointment-info-item">

                    <div class="appointment-info-icon">
                        ☎
                    </div>

                    <div>
                        <strong>Call Us</strong>

                        <p>
                            07031093290
                        </p>

                        <p>
                            08097036144
                        </p>
                    </div>

                </div>


                <div class="appointment-info-item">

                    <div class="appointment-info-icon">
                        ✉
                    </div>

                    <div>
                        <strong>Email</strong>

                        <p>
                            bamideleolusegun30@gmail.com
                        </p>
                    </div>

                </div>


                <div class="appointment-info-item">

                    <div class="appointment-info-icon">
                        ⌖
                    </div>

                    <div>
                        <strong>Location</strong>

                        <p>
                            Plot 6-10, Amusa Salvage Street,
                            Ile-Ise Bus Stop, Along Agbado
                            Crossing Road, Ogun State.
                        </p>
                    </div>

                </div>

            </div>


            <!-- FORM -->

            <div class="appointment-form-box">

                <div class="appointment-form-heading">

                    <h2>
                        Request an Appointment
                    </h2>

                    <p>
                        Please provide your details below.
                    </p>

                </div>


                <?php if (!empty($success)): ?>

                    <div class="appointment-success">
                        <?php echo htmlspecialchars($success); ?>
                    </div>

                <?php endif; ?>


                <?php if (!empty($error)): ?>

                    <div class="appointment-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>

                <?php endif; ?>


                <form
                    action=""
                    method="POST"
                    class="appointment-form"
                >

                    <!-- NAME -->

                    <div class="appointment-field">

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

                    <div class="appointment-field">

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

                    <div class="appointment-field">

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


                    <!-- APPOINTMENT TYPE -->

                    <div class="appointment-field">

                        <label for="appointment_type">
                            Appointment Type *
                        </label>

                        <select
                            id="appointment_type"
                            name="appointment_type"
                            required
                        >

                            <option value="">
                                Select appointment type
                            </option>

                            <option value="Pastoral Counselling">
                                Pastoral Counselling
                            </option>

                            <option value="Prayer Request">
                                Prayer Request
                            </option>

                            <option value="Spiritual Guidance">
                                Spiritual Guidance
                            </option>

                            <option value="Ministry Meeting">
                                Ministry Meeting
                            </option>

                            <option value="General Enquiry">
                                General Enquiry
                            </option>

                        </select>

                    </div>


                    <!-- DATE -->

                    <div class="appointment-row">

                        <div class="appointment-field">

                            <label for="appointment_date">
                                Preferred Date *
                            </label>

                            <input
                                type="date"
                                id="appointment_date"
                                name="appointment_date"
                                min="<?php echo date('Y-m-d'); ?>"
                                required
                            >

                        </div>


                        <!-- TIME -->

                        <div class="appointment-field">

                            <label for="appointment_time">
                                Preferred Time *
                            </label>

                            <input
                                type="time"
                                id="appointment_time"
                                name="appointment_time"
                                required
                            >

                        </div>

                    </div>


                    <!-- MESSAGE -->

                    <div class="appointment-field">

                        <label for="message">
                            Additional Information
                        </label>

                        <textarea
                            id="message"
                            name="message"
                            rows="5"
                            placeholder="Tell us briefly what you would like to discuss..."
                        ></textarea>

                    </div>


                    <!-- SUBMIT -->

                    <button
                        type="submit"
                        class="appointment-submit"
                    >
                        Submit Appointment Request
                        <span>→</span>
                    </button>

                </form>

            </div>

        </div>

    </div>

</section>


<?php
include "includes/footer.php";
?>