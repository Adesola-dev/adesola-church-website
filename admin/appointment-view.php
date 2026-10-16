<?php

include "../includes/db.php";
include "includes/header.php";
include "includes/sidebar.php";


// =========================================================
// GET APPOINTMENT ID
// =========================================================

if (!isset($_GET['id'])) {

    header("Location: appointments.php");
    exit;
}

$id = intval($_GET['id']);


// =========================================================
// GET APPOINTMENT
// =========================================================

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM appointments WHERE id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$appointment = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


if (!$appointment) {

    header("Location: appointments.php");
    exit;
}

?>

<div class="admin-content">

    <div class="admin-page-header">

        <div>

            <h1>
                Appointment Details
            </h1>

            <p>
                View appointment request information.
            </p>

        </div>


        <a
            href="appointments.php"
            class="admin-back-button"
        >
            ← Back to Appointments
        </a>

    </div>


    <div class="appointment-details-card">

        <!-- NAME -->

        <div class="appointment-detail">

            <span>
                Full Name
            </span>

            <strong>
                <?php
                echo htmlspecialchars(
                    $appointment['name']
                );
                ?>
            </strong>

        </div>


        <!-- EMAIL -->

        <div class="appointment-detail">

            <span>
                Email Address
            </span>

            <strong>
                <?php
                echo htmlspecialchars(
                    $appointment['email']
                );
                ?>
            </strong>

        </div>


        <!-- PHONE -->

        <div class="appointment-detail">

            <span>
                Phone Number
            </span>

            <strong>
                <?php
                echo htmlspecialchars(
                    $appointment['phone']
                );
                ?>
            </strong>

        </div>


        <!-- TYPE -->

        <div class="appointment-detail">

            <span>
                Appointment Type
            </span>

            <strong>
                <?php
                echo htmlspecialchars(
                    $appointment['appointment_type']
                );
                ?>
            </strong>

        </div>


        <!-- DATE -->

        <div class="appointment-detail">

            <span>
                Preferred Date
            </span>

            <strong>
                <?php
                echo date(
                    "d F Y",
                    strtotime(
                        $appointment['appointment_date']
                    )
                );
                ?>
            </strong>

        </div>


        <!-- TIME -->

        <div class="appointment-detail">

            <span>
                Preferred Time
            </span>

            <strong>
                <?php
                echo date(
                    "h:i A",
                    strtotime(
                        $appointment['appointment_time']
                    )
                );
                ?>
            </strong>

        </div>


        <!-- STATUS -->

        <div class="appointment-detail">

            <span>
                Status
            </span>

            <strong>

                <span
                    class="appointment-status status-<?php echo $appointment['status']; ?>"
                >
                    <?php
                    echo ucfirst(
                        $appointment['status']
                    );
                    ?>
                </span>

            </strong>

        </div>


        <!-- MESSAGE -->

        <div class="appointment-detail appointment-message">

            <span>
                Additional Information
            </span>

            <p>

                <?php

                if (!empty($appointment['message'])) {

                    echo nl2br(
                        htmlspecialchars(
                            $appointment['message']
                        )
                    );

                } else {

                    echo "No additional information provided.";

                }

                ?>

            </p>

        </div>


        <!-- ACTIONS -->

        <div class="appointment-detail-actions">

            <a
                href="appointments.php?id=<?php echo $appointment['id']; ?>&status=confirmed"
                class="admin-confirm-button"
            >
                Confirm Appointment
            </a>


            <a
                href="appointments.php?id=<?php echo $appointment['id']; ?>&status=completed"
                class="admin-complete-button"
            >
                Mark Completed
            </a>


            <a
                href="appointments.php?id=<?php echo $appointment['id']; ?>&status=cancelled"
                class="admin-cancel-button"
            >
                Cancel
            </a>

        </div>

    </div>

</div>


<?php
include "includes/footer.php";
?>