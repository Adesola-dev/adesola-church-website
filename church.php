<?php
include "includes/header.php";
include "includes/church-navbar.php";
include "includes/db.php";
?>

<!-- =========================================================
     CHURCH HERO
========================================================= -->

<section class="church-hero">

    <div class="church-hero-overlay"></div>

    <div class="church-hero-content">

        <div class="church-label">
            CHURCH & MINISTRY
        </div>

        <h1>
            Welcome to Our
            <span>Church</span>
        </h1>

        <p>
            A place where the Word of God is preached, lives are
            strengthened, and people are encouraged to grow in faith
            and fulfil God's purpose for their lives.
        </p>

        <div class="church-hero-buttons">

            <a href="appointment.php" class="church-gold-button">
                Book an Appointment
                <span>→</span>
            </a>

            <a href="#about-church" class="church-outline-button">
                Learn More
            </a>

        </div>

    </div>

</section>


<!-- =========================================================
     WELCOME / ABOUT
========================================================= -->

<section class="church-welcome" id="about-church">

    <div class="container">

        <div class="church-welcome-grid">

            <div class="church-welcome-image">

                <img
                    src="assets/images/ministry.jpg"
                    alt="Olusegun Church and Ministry"
                >

            </div>

            <div class="church-welcome-content">

                <div class="church-section-label">
                    WELCOME
                </div>

                <h2>
                    A Place to
                    <span>Grow in Faith</span>
                </h2>

                <p>
                    Welcome to our church and ministry. We are committed
                    to the preaching and teaching of the Word of God,
                    helping people grow spiritually and strengthening
                    believers in their walk with Christ.
                </p>

                <p>
                    Through preaching, teaching, prayer, evangelization
                    and fellowship, we seek to win souls, strengthen
                    believers and proclaim the Gospel of Jesus Christ.
                </p>

                <a href="church-about.php" class="church-text-button">
                    Learn More About Us
                    <span>→</span>
                </a>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     OUR MISSION / FOCUS
========================================================= -->

<section class="church-values">

    <div class="container">

        <div class="church-section-heading">

            <div class="church-section-label">
                OUR MISSION
            </div>

            <h2>
                The Word. <span>Faith.</span> Evangelization.
            </h2>

            <p>
                We are committed to the advancement of God's Kingdom
                through biblical teaching, prayer and the proclamation
                of the Gospel.
            </p>

        </div>


        <div class="church-values-grid">

            <!-- FOCUS 1 -->

            <div class="church-value-card">

                <div class="church-value-icon">
                    ✦
                </div>

                <h3>
                    The Word of God
                </h3>

                <p>
                    We believe in teaching and establishing people in
                    the truth of God's Word for spiritual growth and
                    maturity.
                </p>

            </div>


            <!-- FOCUS 2 -->

            <div class="church-value-card">

                <div class="church-value-icon">
                    ✦
                </div>

                <h3>
                    Prayer
                </h3>

                <p>
                    We encourage a life of prayer and dependence on God,
                    seeking His direction, grace and intervention.
                </p>

            </div>


            <!-- FOCUS 3 -->

            <div class="church-value-card">

                <div class="church-value-icon">
                    ✦
                </div>

                <h3>
                    Evangelization
                </h3>

                <p>
                    We are committed to reaching the lost and proclaiming
                    the Gospel of Jesus Christ to people and communities.
                </p>

            </div>


            <!-- FOCUS 4 -->

            <div class="church-value-card">

                <div class="church-value-icon">
                    ✦
                </div>

                <h3>
                    Spiritual Growth
                </h3>

                <p>
                    We seek to strengthen believers and encourage them
                    to grow in their relationship with God and fulfil
                    their calling.
                </p>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     CHURCH SERVICES
========================================================= -->

<section class="church-services">

    <div class="container">

        <div class="church-section-heading">

            <div class="church-section-label">
                JOIN US
            </div>

            <h2>
                Our <span>Services</span>
            </h2>

            <p>
                Join us for worship, prayer, biblical teaching and
                fellowship as we grow together in faith.
            </p>

        </div>


        <div class="church-services-grid">

            <div class="church-service-card">

                <div class="church-service-number">
                    01
                </div>

                <h3>
                    Sunday Service
                </h3>

                <p>
                    Join us for worship, the Word of God, prayer
                    and fellowship.
                </p>

                <div class="church-service-time">
                    Sunday
                </div>

            </div>


            <div class="church-service-card">

                <div class="church-service-number">
                    02
                </div>

                <h3>
                    Bible Study
                </h3>

                <p>
                    Grow deeper in the knowledge of God's Word
                    through biblical teaching and study.
                </p>

                <div class="church-service-time">
                    Midweek
                </div>

            </div>


            <div class="church-service-card">

                <div class="church-service-number">
                    03
                </div>

                <h3>
                    Prayer Meeting
                </h3>

                <p>
                    Come together in prayer, seeking God's
                    direction, grace and intervention.
                </p>

                <div class="church-service-time">
                    Weekly
                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     UPCOMING CHURCH EVENTS
========================================================= -->

<section class="church-events">

    <div class="container">

        <div class="church-section-heading">

            <div class="church-section-label">
                WHAT'S HAPPENING
            </div>

            <h2>
                Upcoming <span>Church Events</span>
            </h2>

            <p>
                Stay connected with our church programs, meetings
                and special events.
            </p>

        </div>


        <div class="church-events-grid">

            <?php

            $event_sql = "
                SELECT *
                FROM events
                WHERE category = 'Church'
                  AND event_date >= CURDATE()
                ORDER BY event_date ASC, event_time ASC
                LIMIT 3
            ";
            /* =========================
   UPCOMING CHURCH SERMONS
========================= */

$sermon_result = mysqli_query(
    $conn,
    "SELECT *
     FROM sermons
     WHERE category = 'Church'
     ORDER BY sermon_date DESC, id DESC
     LIMIT 3"
);

            $event_result = mysqli_query($conn, $event_sql);

            if ($event_result && mysqli_num_rows($event_result) > 0):

                while ($event = mysqli_fetch_assoc($event_result)):

            ?>

                <div class="church-event-card">

                    <?php if (!empty($event['image'])): ?>

                        <div class="church-event-image">

                            <img
                                src="assets/uploads/events/<?php echo htmlspecialchars($event['image']); ?>"
                                alt="<?php echo htmlspecialchars($event['title']); ?>"
                            >

                        </div>

                    <?php endif; ?>


                    <div class="church-event-content">

                        <div class="church-event-date">

                            <?php
                            echo date(
                                "d M Y",
                                strtotime($event['event_date'])
                            );
                            ?>

                        </div>


                        <h3>
                            <?php
                            echo htmlspecialchars($event['title']);
                            ?>
                        </h3>


                        <?php if (!empty($event['event_time'])): ?>

                            <div class="church-event-meta">

                                🕐
                                <?php
                                echo date(
                                    "g:i A",
                                    strtotime($event['event_time'])
                                );
                                ?>

                            </div>

                        <?php endif; ?>


                        <?php if (!empty($event['location'])): ?>

                            <div class="church-event-meta">

                                📍
                                <?php
                                echo htmlspecialchars($event['location']);
                                ?>

                            </div>

                        <?php endif; ?>


                        <?php if (!empty($event['description'])): ?>

                            <p>
                                <?php
                                echo htmlspecialchars(
                                    $event['description']
                                );
                                ?>
                            </p>

                        <?php endif; ?>

                    </div>

                </div>

            <?php

                endwhile;

            else:

            ?>

                <div class="church-no-events">

                    <p>
                        No upcoming church events at the moment.
                        Please check back soon.
                    </p>

                </div>

            <?php endif; ?>

        </div>


        <div class="church-events-button">

            <a href="events.php" class="church-text-button">
                View All Church Events
                <span>→</span>
            </a>

        </div>

    </div>

</section>


<!-- =========================================================
     MINISTRY CTA
========================================================= -->

<section class="church-cta">

    <div class="church-cta-overlay"></div>

    <div class="container">

        <div class="church-cta-content">

            <div class="church-section-label">
                CONNECT WITH US
            </div>

            <h2>
                We Are Here to
                <span>Serve You</span>
            </h2>

            <p>
                If you need prayer, spiritual guidance, counselling
                or would simply like to connect with the ministry,
                we are here to listen and serve.
            </p>

            <a
                href="appointment.php"
                class="church-gold-button"
            >
                Book an Appointment
                <span>→</span>
            </a>

        </div>

    </div>

</section>


<!-- =========================================================
     CONTACT
========================================================= -->

<section class="church-contact">

    <div class="container">

        <div class="church-section-heading">

            <div class="church-section-label">
                CONTACT US
            </div>

            <h2>
                We Would Love to
                <span>Hear From You</span>
            </h2>

        </div>


        <div class="church-contact-grid">

            <!-- PHONE -->

            <div class="church-contact-card">

                <div class="church-contact-icon">
                    ☎
                </div>

                <h3>
                    Call Us
                </h3>

                <p>
                    07031093290
                </p>

                <p>
                    08097036144
                </p>

            </div>


            <!-- EMAIL -->

            <div class="church-contact-card">

                <div class="church-contact-icon">
                    ✉
                </div>

                <h3>
                    Email Us
                </h3>

                <p>
                    bamideleolusegun30@gmail.com
                </p>

            </div>


            <!-- ADDRESS -->

            <div class="church-contact-card">

                <div class="church-contact-icon">
                    ⌖
                </div>

                <h3>
                    Visit Us
                </h3>

                <p>
                    Plot 6-10, Amusa Salvage Street,
                    Ile-Ise Bus Stop, Along Agbado Crossing Road,
                    Ogun State.
                </p>

            </div>

        </div>


        <div class="church-contact-button">

            <a
                href="appointment.php"
                class="church-gold-button"
            >
                Book an Appointment
                <span>→</span>
            </a>

        </div>

    </div>

</section>


<?php
include "includes/footer.php";
?>