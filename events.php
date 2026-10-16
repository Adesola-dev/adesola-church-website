<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/db.php';

/* Get Seminary / School events only */
$result = mysqli_query(
    $conn,
    "SELECT *
     FROM events
     WHERE category = 'Seminary / School'
     ORDER BY event_date ASC, event_time ASC"
);
?>

<section class="events-hero">
    <div class="container">
        <h1>Events</h1>
        <p>Stay connected with upcoming events and activities at Olusegun Theological Seminary.</p>
    </div>
</section>

<section class="public-events">
    <div class="container">

        <div class="section-heading">
            <span>WHAT'S HAPPENING</span>
            <h2>Upcoming Events</h2>
            <p>
                Explore our upcoming programmes, conferences, meetings
                and other activities.
            </p>
        </div>

        <?php if ($result && mysqli_num_rows($result) > 0): ?>

            <div class="public-events-grid">

                <?php while ($row = mysqli_fetch_assoc($result)): ?>

                    <article class="public-event-card">

                        <?php if (!empty($row['image'])): ?>

                            <div class="public-event-image">
                                <img
                                    src="assets/uploads/events/<?php echo htmlspecialchars($row['image']); ?>"
                                    alt="<?php echo htmlspecialchars($row['title']); ?>"
                                >
                            </div>

                        <?php else: ?>

                            <div class="public-event-image no-image">
                                📅
                            </div>

                        <?php endif; ?>


                        <div class="public-event-content">

                            <h3>
                                <?php echo htmlspecialchars($row['title']); ?>
                            </h3>


                            <div class="public-event-meta">

                                <p>
                                    📅
                                    <?php
                                    echo date(
                                        'F d, Y',
                                        strtotime($row['event_date'])
                                    );
                                    ?>
                                </p>


                                <?php if (!empty($row['event_time'])): ?>

                                    <p>
                                        🕐
                                        <?php
                                        echo date(
                                            'g:i A',
                                            strtotime($row['event_time'])
                                        );
                                        ?>
                                    </p>

                                <?php endif; ?>


                                <?php if (!empty($row['location'])): ?>

                                    <p>
                                        📍
                                        <?php echo htmlspecialchars($row['location']); ?>
                                    </p>

                                <?php endif; ?>

                            </div>


                            <?php if (!empty($row['description'])): ?>

                                <p class="public-event-description">
                                    <?php
                                    echo htmlspecialchars($row['description']);
                                    ?>
                                </p>

                            <?php endif; ?>

                        </div>

                    </article>

                <?php endwhile; ?>

            </div>

        <?php else: ?>

            <div class="no-events">

                <div class="no-events-icon">
                    📅
                </div>

                <h3>No Upcoming Events</h3>

                <p>
                    There are currently no events available.
                    Please check back soon.
                </p>

            </div>

        <?php endif; ?>

    </div>
</section>


<style>

.events-hero {
    background:
        linear-gradient(
            rgba(23, 26, 99, 0.88),
            rgba(15, 18, 74, 0.93)
        ),
        url("assets/images/ministry.jpg")
        center center / cover no-repeat;

    padding: 100px 20px;
    text-align: center;
    color: #fff;
}


.events-hero h1 {
    font-size: 48px;
    margin-bottom: 15px;
}


.events-hero p {
    max-width: 700px;
    margin: auto;
    font-size: 17px;
    line-height: 1.7;
    opacity: 0.9;
}


.public-events {
    padding: 80px 20px;
    background: #fff;
}


.section-heading {
    text-align: center;
    max-width: 700px;
    margin: 0 auto 45px;
}


.section-heading span {
    color: #D9A400;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 1.5px;
}


.section-heading h2 {
    color: #171A63;
    font-size: 34px;
    margin: 10px 0;
}


.section-heading p {
    color: #666;
    line-height: 1.7;
}


.public-events-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
}


.public-event-card {
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    overflow: hidden;
    transition: 0.3s;
}


.public-event-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
}


.public-event-image {
    width: 100%;
    height: 220px;
    overflow: hidden;
}


.public-event-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}


.public-event-image.no-image {
    background: #171A63;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 50px;
}


.public-event-content {
    padding: 22px;
}


.public-event-content h3 {
    color: #171A63;
    font-size: 20px;
    line-height: 1.4;
    margin-bottom: 15px;
}


.public-event-meta {
    margin-bottom: 15px;
}


.public-event-meta p {
    color: #666;
    font-size: 13px;
    margin-bottom: 7px;
}


.public-event-description {
    color: #666;
    font-size: 14px;
    line-height: 1.7;
}


.no-events {
    max-width: 600px;
    margin: 0 auto;
    text-align: center;
    padding: 60px 20px;
    background: #f5f6fc;
    border-radius: 12px;
}


.no-events-icon {
    font-size: 50px;
    margin-bottom: 15px;
}


.no-events h3 {
    color: #171A63;
    margin-bottom: 10px;
}


.no-events p {
    color: #666;
    line-height: 1.6;
}


@media (max-width: 900px) {

    .public-events-grid {
        grid-template-columns: repeat(2, 1fr);
    }

}


@media (max-width: 600px) {

    .events-hero {
        padding: 70px 20px;
    }


    .events-hero h1 {
        font-size: 36px;
    }


    .public-events {
        padding: 60px 20px;
    }


    .section-heading h2 {
        font-size: 28px;
    }


    .public-events-grid {
        grid-template-columns: 1fr;
    }

}

</style>


<?php
include 'includes/footer.php';
?>