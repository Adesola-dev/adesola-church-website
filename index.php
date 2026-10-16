<?php
include "includes/header.php";
include "includes/navbar.php";
?>

<!-- =========================================================
     OTS LANDING PAGE
     SCHOOL / CHURCH SELECTION
========================================================= -->

<section class="ots-landing">

    <!-- Background Overlay -->
    <div class="ots-landing-overlay"></div>

    <div class="ots-landing-content">

        <!-- LOGO -->
        <div class="ots-logo">
            <img
                src="assets/images/logo.png"
                alt="Olusegun Theological Seminary Logo"
            >
        </div>

        <!-- BRAND NAME -->
        <div class="ots-brand">
            OLUSEGUN THEOLOGICAL SEMINARY
        </div>

        <!-- MAIN HEADING -->
        <h1>
            Knowledge, Faith
            <span>&amp;</span>
            Purpose
        </h1>

        <!-- INTRODUCTION -->
        <p class="ots-intro">
            Welcome to Olusegun Theological Seminary.
            Explore our theological education programmes,
            or connect with our church and ministry.
        </p>

        <!-- =================================================
             SCHOOL / CHURCH OPTIONS
        ================================================== -->

        <div class="ots-options">

            <!-- ================= SCHOOL ================= -->

            <div class="ots-option">

                <div class="ots-option-icon school-icon">

                    <svg
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path d="M12 3L1 8.5L12 14L21 9.5V16H23V8.5L12 3Z"/>
                        <path d="M5 11V16.5C5 16.5 8 20 12 20C16 20 19 16.5 19 16.5V11L12 14.5L5 11Z"/>
                    </svg>

                </div>

                <div class="ots-option-label">
                    THE SEMINARY
                </div>

                <h2>
                    Explore the School
                </h2>

                <p>
                    Discover our theological programmes,
                    admission requirements, academic information
                    and opportunities for ministry training.
                </p>

                <a
                    href="programs.php"
                    class="ots-primary-button"
                >
                    Explore School
                    <span>→</span>
                </a>

            </div>


            <!-- ================= CHURCH ================= -->

            <div class="ots-option">

                <div class="ots-option-icon church-icon">

                    <svg
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path d="M12 2L9 5H11V8H8V11H11V14H13V11H16V8H13V5H15L12 2Z"/>
                        <path d="M4 10H7V21H17V10H20V21H4V10Z"/>
                        <path d="M10 15H14V21H10V15Z"/>
                    </svg>

                </div>

                <div class="ots-option-label">
                    CHURCH &amp; MINISTRY
                </div>

                <h2>
                    Visit the Church
                </h2>

                <p>
                    Connect with our church and ministry,
                    learn about our services, meet with us
                    and book an appointment.
                </p>

                <div class="ots-church-buttons">

                    <a
                        href="church.php"
                        class="ots-primary-button"
                    >
                        Visit Church
                        <span>→</span>
                    </a>

                    <a
                        href="appointment.php"
                        class="ots-secondary-button"
                    >
                        Book an Appointment
                    </a>

                </div>

            </div>

        </div>


        <!-- BOTTOM STATEMENT -->

        <div class="ots-bottom-line">

            <span></span>

            <p>
                Raising people of knowledge, faith and purpose
                for God's Kingdom.
            </p>

            <span></span>

        </div>

    </div>

</section>


<?php
include "includes/footer.php";
?>