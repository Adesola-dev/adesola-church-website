<?php

include 'includes/db.php';
include 'includes/header.php';
include 'includes/church-navbar.php';


/* =========================
   GET GALLERY IMAGES
========================= */

$result = mysqli_query(
    $conn,
    "SELECT *
     FROM gallery
     WHERE category = 'Church'
     ORDER BY created_at DESC"
);

?>

<!-- =========================================================
     CHURCH GALLERY HERO
========================================================= -->

<section class="gallery-hero">

    <div class="container">

        <div class="gallery-hero-content">

            <span class="gallery-eyebrow">
                OUR GALLERY
            </span>

            <h1>
                Moments of Faith & Fellowship
            </h1>

            <p>
                Explore moments from our worship, ministry activities,
                church gatherings and special events.
            </p>

            <div class="gallery-hero-line"></div>

        </div>

    </div>

</section>


<!-- =========================================================
     GALLERY INTRO
========================================================= -->

<section class="gallery-intro section">

    <div class="container">

        <div class="section-heading center">

            <span class="section-label">
                OUR JOURNEY
            </span>

            <h2>
                Life in Our Church
            </h2>

            <p>
                Take a look at moments of worship, fellowship,
                ministry and the activities that bring our
                church family together.
            </p>

        </div>


        <!-- FILTER BUTTONS -->

       <div class="gallery-filters">

    <button
        class="gallery-filter active"
        data-filter="all"
    >
        All
    </button>

    <button
        class="gallery-filter"
        data-filter="church"
    >
        Church
    </button>

</div>


        <!-- GALLERY GRID -->

        <div class="gallery-grid">

            <?php if ($result && mysqli_num_rows($result) > 0): ?>

                <?php while ($row = mysqli_fetch_assoc($result)): ?>

                    <?php

                    $category = strtolower(
                        trim($row['category'])
                    );

                    $image_path =
                        "assets/uploads/gallery/" .
                        htmlspecialchars($row['image']);

                    $title =
                        htmlspecialchars($row['title']);

                    ?>

                    <div
                        class="gallery-item"
                        data-category="<?php echo htmlspecialchars($category); ?>"
                    >

                        <div class="gallery-card">

                            <!-- IMAGE -->

                            <img
                                src="<?php echo $image_path; ?>"
                                alt="<?php echo $title; ?>"
                            >


                            <!-- OVERLAY -->

                            <div class="gallery-overlay">

                                <div>

                                    <span>
                                        <?php
                                        echo htmlspecialchars(
                                            $row['category']
                                        );
                                        ?>
                                    </span>

                                    <h3>
                                        <?php
                                        echo $title;
                                        ?>
                                    </h3>

                                </div>


                                <!-- VIEW BUTTON -->

                                <button
                                    class="gallery-view"
                                    data-image="<?php echo $image_path; ?>"
                                    data-title="<?php echo $title; ?>"
                                >
                                    +
                                </button>

                            </div>

                        </div>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <!-- EMPTY STATE -->

                <div
                    class="gallery-item"
                    data-category="all"
                    style="grid-column: 1 / -1;"
                >

                    <div class="gallery-card gallery-placeholder">

                        <div class="gallery-placeholder-content">

                            <span>
                                GALLERY
                            </span>

                            <h3>
                                Gallery Coming Soon
                            </h3>

                            <p>
                                Photos from our church worship,
                                activities and events will appear here.
                            </p>

                        </div>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>


<!-- =========================================================
     CHURCH CTA
========================================================= -->

<section class="gallery-cta">

    <div class="container">

        <div class="gallery-cta-content">

            <span class="section-label">
                CONNECT WITH US
            </span>

            <h2>
                Be Part of Our Church Family
            </h2>

            <p>
                Join us for worship, prayer, fellowship and
                the teaching of God's Word.
            </p>

            <div class="gallery-cta-buttons">

                <a
                    href="church.php"
                    class="btn btn-primary"
                >
                    Visit Our Church
                </a>

                <a
                    href="appointment.php"
                    class="btn btn-outline"
                >
                    Book an Appointment
                </a>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     IMAGE LIGHTBOX
========================================================= -->

<div
    class="gallery-lightbox"
    id="galleryLightbox"
>

    <button
        class="gallery-close"
        id="galleryClose"
    >
        &times;
    </button>


    <div class="gallery-lightbox-content">

        <img
            id="lightboxImage"
            src=""
            alt=""
        >

        <div class="gallery-lightbox-caption">

            <h3 id="lightboxTitle"></h3>

        </div>

    </div>

</div>


<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {

        /* =========================
           FILTER
        ========================= */

        const filterButtons =
            document.querySelectorAll(
                ".gallery-filter"
            );

        const galleryItems =
            document.querySelectorAll(
                ".gallery-item"
            );


        filterButtons.forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    function () {

                        filterButtons.forEach(
                            function (btn) {

                                btn.classList.remove(
                                    "active"
                                );

                            }
                        );


                        this.classList.add(
                            "active"
                        );


                        const filter =
                            this.getAttribute(
                                "data-filter"
                            );


                        galleryItems.forEach(
                            function (item) {

                                const category =
                                    item.getAttribute(
                                        "data-category"
                                    );


                                if (
                                    filter === "all" ||
                                    category === filter
                                ) {

                                    item.style.display =
                                        "block";

                                } else {

                                    item.style.display =
                                        "none";

                                }

                            }
                        );

                    }
                );

            }
        );


        /* =========================
           LIGHTBOX
        ========================= */

        const lightbox =
            document.getElementById(
                "galleryLightbox"
            );


        const lightboxImage =
            document.getElementById(
                "lightboxImage"
            );


        const lightboxTitle =
            document.getElementById(
                "lightboxTitle"
            );


        const closeButton =
            document.getElementById(
                "galleryClose"
            );


        const viewButtons =
            document.querySelectorAll(
                ".gallery-view"
            );


        viewButtons.forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    function () {

                        const image =
                            this.getAttribute(
                                "data-image"
                            );


                        const title =
                            this.getAttribute(
                                "data-title"
                            );


                        lightboxImage.src =
                            image;


                        lightboxImage.alt =
                            title;


                        lightboxTitle.textContent =
                            title;


                        lightbox.classList.add(
                            "show"
                        );


                        document.body.style.overflow =
                            "hidden";

                    }
                );

            }
        );


        /* =========================
           CLOSE LIGHTBOX
        ========================= */

        function closeLightbox() {

            lightbox.classList.remove(
                "show"
            );

            document.body.style.overflow =
                "";

        }


        closeButton.addEventListener(
            "click",
            closeLightbox
        );


        lightbox.addEventListener(
            "click",
            function (event) {

                if (
                    event.target === lightbox
                ) {

                    closeLightbox();

                }

            }
        );


        document.addEventListener(
            "keydown",
            function (event) {

                if (
                    event.key === "Escape"
                ) {

                    closeLightbox();

                }

            }
        );

    }
);

</script>


<?php

include 'includes/footer.php';

?>