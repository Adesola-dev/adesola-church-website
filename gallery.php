<?php

include 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';


/* =========================
   GET GALLERY IMAGES
========================= */

$result = mysqli_query(
    $conn,
    "SELECT *
     FROM gallery
     WHERE category = 'Seminary / School'
     ORDER BY created_at DESC"
);

?>

<!-- GALLERY HERO -->
<section class="gallery-hero">

    <div class="container">

        <div class="gallery-hero-content">

            <span class="gallery-eyebrow">
                OUR GALLERY
            </span>

            <h1>
                Moments, Ministry & Memories
            </h1>

            <p>
                Explore moments from Olusegun Theological Seminary,
                our ministry activities, academic community and events.
            </p>

            <div class="gallery-hero-line"></div>

        </div>

    </div>

</section>


<!-- GALLERY INTRO -->
<section class="gallery-intro section">

    <div class="container">

        <div class="section-heading center">

            <span class="section-label">
                EXPLORE OTS
            </span>

            <h2>
                Life at Olusegun Theological Seminary
            </h2>

            <p>
                Take a look at some of the people, places and moments
                that form part of our journey of theological education
                and ministry preparation.
            </p>

        </div>


        <!-- FILTER BUTTONS -->
        <div class="gallery-filters">

    <button
        class="gallery-filter active"
        data-filter="all"
    >
        All Photos
    </button>

</div>


        <!-- GALLERY GRID -->
        <div class="gallery-grid">


            <?php if ($result && mysqli_num_rows($result) > 0): ?>


                <?php while ($row = mysqli_fetch_assoc($result)): ?>

                    <?php

                    /*
                     * Convert database category
                     * to lowercase for filtering.
                     */
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
                                Photos from Olusegun Theological Seminary
                                will appear here.
                            </p>

                        </div>

                    </div>

                </div>


            <?php endif; ?>


        </div>

    </div>

</section>


<!-- GALLERY CTA -->
<section class="gallery-cta">

    <div class="container">

        <div class="gallery-cta-content">

            <span class="section-label">
                JOIN OUR JOURNEY
            </span>

            <h2>
                Be Part of What God Is Building
            </h2>

            <p>
                Discover theological education designed to equip you
                for greater service and ministry.
            </p>


            <div class="gallery-cta-buttons">

                <a
                    href="programs.php"
                    class="btn btn-primary"
                >
                    Explore Programmes
                </a>

                <a
                    href="admission.php"
                    class="btn btn-outline"
                >
                    Apply for Admission
                </a>

            </div>

        </div>

    </div>

</section>


<!-- IMAGE LIGHTBOX -->
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