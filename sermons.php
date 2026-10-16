<?php
require_once "includes/db.php";
require_once "includes/header.php";
require_once "includes/navbar.php";

/* Get Seminary / School sermons only */
$result = mysqli_query(
    $conn,
    "SELECT *
     FROM sermons
     WHERE category = 'Seminary / School'
     ORDER BY sermon_date DESC, id DESC"
);
?>

<style>
/* =========================================================
   OTS SEMINARY SERMONS PAGE
========================================================= */

.sermons-page {
    background: #f6f7fb;
    min-height: 500px;
}

/* HERO */
.sermons-hero {
    background:
        linear-gradient(
            rgba(23, 26, 99, 0.94),
            rgba(15, 18, 74, 0.97)
        );
    padding: 75px 20px;
    text-align: center;
    color: #ffffff;
}

.sermons-hero-inner {
    max-width: 850px;
    margin: 0 auto;
}

.sermons-hero .hero-label {
    display: inline-block;
    color: #f3d36a;
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 3px;
    text-transform: uppercase;
    margin-bottom: 15px;
}

.sermons-hero h1 {
    color: #ffffff;
    font-size: 48px;
    line-height: 1.2;
    margin: 0 0 18px;
}

.sermons-hero p {
    color: rgba(255,255,255,0.88);
    font-size: 17px;
    line-height: 1.8;
    margin: 0 auto;
    max-width: 700px;
}

/* MAIN CONTENT */
.sermons-content {
    max-width: 1180px;
    margin: 0 auto;
    padding: 75px 20px;
}

/* INTRO */
.sermons-intro {
    max-width: 760px;
    margin: 0 auto 50px;
    text-align: center;
}

.sermons-intro .intro-label {
    display: inline-block;
    color: #d9a400;
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 12px;
}

.sermons-intro h2 {
    color: #171a63;
    font-size: 38px;
    line-height: 1.25;
    margin: 0 0 18px;
}

.sermons-intro p {
    color: #666666;
    font-size: 16px;
    line-height: 1.8;
    margin: 0;
}

/* GRID */
.sermons-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

/* CARD */
.sermon-card {
    background: #ffffff;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid #e5e6ee;
    box-shadow: 0 8px 30px rgba(23,26,99,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.sermon-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 18px 40px rgba(23,26,99,0.15);
}

/* IMAGE */
.sermon-image {
    height: 225px;
    position: relative;
    overflow: hidden;
    background: #171a63;
}

.sermon-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.5s ease;
}

.sermon-card:hover .sermon-image img {
    transform: scale(1.06);
}

.sermon-image-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to top,
        rgba(15,18,74,0.75),
        transparent 55%
    );
}

/* NO IMAGE */
.sermon-no-image {
    height: 225px;
    display: flex;
    align-items: center;
    justify-content: center;
    background:
        linear-gradient(
            135deg,
            #171a63,
            #252a82
        );
}

.sermon-no-image span {
    font-size: 55px;
}

/* CARD CONTENT */
.sermon-body {
    padding: 26px;
}

.sermon-tag {
    display: inline-block;
    background: #fff8df;
    color: #b88600;
    padding: 7px 13px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.5px;
    margin-bottom: 15px;
}

.sermon-body h3 {
    color: #171a63;
    font-size: 21px;
    line-height: 1.4;
    margin: 0 0 18px;
}

.sermon-meta {
    border-top: 1px solid #eeeeee;
    padding-top: 15px;
}

.sermon-meta p {
    color: #666666;
    font-size: 14px;
    margin: 8px 0;
}

.sermon-meta strong {
    color: #171a63;
}

.sermon-description {
    color: #777777;
    font-size: 14px;
    line-height: 1.7;
    margin: 18px 0;
}

/* BUTTONS */
.sermon-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.sermon-button {
    display: inline-block;
    padding: 11px 17px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    transition: all 0.3s ease;
}

.sermon-watch {
    background: #171a63;
    color: #ffffff;
}

.sermon-watch:hover {
    background: #0f124a;
    color: #ffffff;
}

.sermon-listen {
    background: #ffffff;
    color: #171a63;
    border: 1px solid #171a63;
}

.sermon-listen:hover {
    background: #171a63;
    color: #ffffff;
}

/* EMPTY STATE */
.sermons-empty {
    max-width: 700px;
    margin: 0 auto;
    padding: 65px 30px;
    text-align: center;
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid #e5e6ee;
    box-shadow: 0 8px 30px rgba(23,26,99,0.07);
}

.sermons-empty-icon {
    width: 75px;
    height: 75px;
    margin: 0 auto 22px;
    border-radius: 50%;
    background: #f0f1fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
}

.sermons-empty h3 {
    color: #171a63;
    font-size: 25px;
    margin: 0 0 12px;
}

.sermons-empty p {
    color: #666666;
    font-size: 15px;
    line-height: 1.8;
    margin: 0;
}

/* CTA */
.sermons-cta {
    background:
        linear-gradient(
            rgba(23,26,99,0.97),
            rgba(15,18,74,0.99)
        );
    padding: 75px 20px;
    text-align: center;
    color: #ffffff;
}

.sermons-cta-inner {
    max-width: 750px;
    margin: 0 auto;
}

.sermons-cta h2 {
    color: #ffffff;
    font-size: 36px;
    margin: 0 0 15px;
}

.sermons-cta p {
    color: rgba(255,255,255,0.85);
    font-size: 16px;
    line-height: 1.8;
    margin: 0 0 30px;
}

.sermons-cta-button {
    display: inline-block;
    background: #d9a400;
    color: #171a63;
    padding: 14px 25px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 800;
    transition: all 0.3s ease;
}

.sermons-cta-button:hover {
    background: #f3d36a;
    color: #171a63;
}

/* RESPONSIVE */
@media (max-width: 992px) {

    .sermons-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .sermons-hero h1 {
        font-size: 42px;
    }
}

@media (max-width: 650px) {

    .sermons-grid {
        grid-template-columns: 1fr;
    }

    .sermons-hero {
        padding: 60px 20px;
    }

    .sermons-hero h1 {
        font-size: 34px;
    }

    .sermons-hero p {
        font-size: 15px;
    }

    .sermons-content {
        padding: 55px 15px;
    }

    .sermons-intro h2 {
        font-size: 30px;
    }

    .sermons-cta h2 {
        font-size: 29px;
    }
}
</style>


<!-- =========================================================
     HERO
========================================================= -->

<section class="sermons-hero">
    <div class="sermons-hero-inner">

        <span class="hero-label">
            Olusegun Theological Seminary
        </span>

        <h1>
            Seminary Sermons
        </h1>

        <p>
            Biblical teachings, messages and spiritual resources
            designed to strengthen faith and equip men and women
            for ministry.
        </p>

    </div>
</section>


<!-- =========================================================
     SERMON CONTENT
========================================================= -->

<section class="sermons-page">

    <div class="sermons-content">

        <div class="sermons-intro">

            <span class="intro-label">
                Teachings &amp; Messages
            </span>

            <h2>
                Grow in Knowledge, Faith &amp; Purpose
            </h2>

            <p>
                Explore sermons and teachings from Olusegun Theological
                Seminary and deepen your understanding of the Word of God.
            </p>

        </div>


        <?php if ($result && mysqli_num_rows($result) > 0): ?>

            <div class="sermons-grid">

                <?php while ($sermon = mysqli_fetch_assoc($result)): ?>

                    <article class="sermon-card">

                        <?php if (!empty($sermon['thumbnail'])): ?>

                            <div class="sermon-image">

                                <img
                                    src="assets/uploads/sermons/<?php echo htmlspecialchars($sermon['thumbnail']); ?>"
                                    alt="<?php echo htmlspecialchars($sermon['title']); ?>"
                                >

                                <div class="sermon-image-overlay"></div>

                            </div>

                        <?php else: ?>

                            <div class="sermon-no-image">
                                <span>🎙️</span>
                            </div>

                        <?php endif; ?>


                        <div class="sermon-body">

                            <span class="sermon-tag">
                                Seminary / School
                            </span>

                            <h3>
                                <?php echo htmlspecialchars($sermon['title']); ?>
                            </h3>


                            <div class="sermon-meta">

                                <p>
                                    <strong>Minister:</strong>
                                    <?php echo htmlspecialchars($sermon['minister']); ?>
                                </p>

                                <?php if (!empty($sermon['sermon_date'])): ?>

                                    <p>
                                        <strong>Date:</strong>
                                        <?php
                                        echo date(
                                            "F j, Y",
                                            strtotime($sermon['sermon_date'])
                                        );
                                        ?>
                                    </p>

                                <?php endif; ?>

                            </div>


                            <?php if (!empty($sermon['description'])): ?>

                                <p class="sermon-description">
                                    <?php
                                    echo htmlspecialchars($sermon['description']);
                                    ?>
                                </p>

                            <?php endif; ?>


                            <div class="sermon-buttons">

                                <?php if (!empty($sermon['youtube_url'])): ?>

                                    <a
                                        href="<?php echo htmlspecialchars($sermon['youtube_url']); ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="sermon-button sermon-watch"
                                    >
                                        Watch Sermon
                                    </a>

                                <?php endif; ?>


                                <?php if (!empty($sermon['audio_url'])): ?>

                                    <a
                                        href="<?php echo htmlspecialchars($sermon['audio_url']); ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="sermon-button sermon-listen"
                                    >
                                        Listen to Audio
                                    </a>

                                <?php endif; ?>

                            </div>

                        </div>

                    </article>

                <?php endwhile; ?>

            </div>


        <?php else: ?>

            <div class="sermons-empty">

                <div class="sermons-empty-icon">
                    🎙️
                </div>

                <h3>
                    No Seminary Sermons Available
                </h3>

                <p>
                    Sermons and teachings will appear here once they
                    are added through the administration dashboard.
                </p>

            </div>

        <?php endif; ?>

    </div>

</section>


<!-- =========================================================
     CTA
========================================================= -->

<section class="sermons-cta">

    <div class="sermons-cta-inner">

        <h2>
            Grow in Knowledge, Faith &amp; Purpose
        </h2>

        <p>
            Discover our theological programs and take the next step
            toward deeper biblical knowledge and ministry preparation.
        </p>

        <a
            href="programs.php"
            class="sermons-cta-button"
        >
            Explore Our Programs
        </a>

    </div>

</section>


<?php require_once "includes/footer.php"; ?>