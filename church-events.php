<?php
include "includes/header.php";
include "includes/church-navbar.php";
include "includes/db.php";
?>

<!-- =========================================================
     CHURCH EVENTS HERO
========================================================= -->

<section class="church-hero">

    <div class="church-hero-overlay"></div>

    <div class="church-hero-content">

        <div class="church-label">
            CHURCH & MINISTRY
        </div>

        <h1>
            Church
            <span>Events</span>
        </h1>

        <p>
            Stay connected with our church programs, meetings,
            worship gatherings and special events.
        </p>

        <div class="church-hero-buttons">

            <a href="church.php" class="church-gold-button">
                Back to Church
                <span>→</span>
            </a>

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
                Discover upcoming programs and activities
                happening within the church.
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
            ";

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

    </div>

</section>


<!-- =========================================================
     CONTACT / CTA
========================================================= -->

<section class="church-cta">

    <div class="church-cta-overlay"></div>

    <div class="container">

        <div class="church-cta-content">

            <div class="church-section-label">
                CONNECT WITH US
            </div>

            <h2>
                Be Part of What
                <span>God Is Doing</span>
            </h2>

            <p>
                Join us as we worship, pray, learn God's Word
                and fellowship together.
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


<?php
include "includes/footer.php";
?>