<?php

include 'includes/db.php';

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Personal Information
    $full_name = trim($_POST['full_name'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $date_of_birth = trim($_POST['date_of_birth'] ?? '');

    // Contact Information
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    // Education
    $highest_qualification = trim($_POST['highest_qualification'] ?? '');
    $institution = trim($_POST['institution'] ?? '');

    // Church / Ministry
    $church_name = trim($_POST['church_name'] ?? '');
    $ministry_role = trim($_POST['ministry_role'] ?? '');
    $years_in_ministry = trim($_POST['years_in_ministry'] ?? '');

    // Application
    $reason = trim($_POST['reason'] ?? '');

    // Basic validation
    if (
        empty($full_name) ||
        empty($email) ||
        empty($phone) ||
        empty($highest_qualification) ||
        empty($reason)
    ) {

        $error = "Please fill in all required fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    } else {

        $sql = "INSERT INTO applications
                (
                    full_name,
                    gender,
                    date_of_birth,
                    email,
                    phone,
                    address,
                    highest_qualification,
                    institution,
                    church_name,
                    ministry_role,
                    years_in_ministry,
                    reason
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "ssssssssssss",
                $full_name,
                $gender,
                $date_of_birth,
                $email,
                $phone,
                $address,
                $highest_qualification,
                $institution,
                $church_name,
                $ministry_role,
                $years_in_ministry,
                $reason
            );

            if (mysqli_stmt_execute($stmt)) {

                $success = "Your application has been submitted successfully. We will contact you soon.";

            } else {

                $error = "Unable to submit your application. Please try again.";

            }

            mysqli_stmt_close($stmt);

        } else {

            $error = "Database error. Please contact the administrator.";

        }
    }
}

include 'includes/header.php';
include 'includes/navbar.php';

?>

<section class="page-hero">
    <div class="container">
        <h1>Apply to OTS</h1>
        <p>Begin your journey of theological education and ministry preparation.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <?php if (!empty($success)): ?>

            <div class="form-message success-message">
                <?php echo htmlspecialchars($success); ?>
            </div>

        <?php endif; ?>

        <?php if (!empty($error)): ?>

            <div class="form-message error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php endif; ?>

        <div class="application-intro">
            <h2>Application Form</h2>

            <p>
                Please complete the form below with accurate information.
                Fields marked with <strong>*</strong> are required.
            </p>
        </div>

        <form method="POST" action="apply.php" class="application-form">

            <!-- PERSONAL INFORMATION -->
            <div class="form-section">

                <h3>Personal Information</h3>

                <div class="form-grid">

                    <div class="form-group full-width">
                        <label for="full_name">
                            Full Name *
                        </label>

                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">

                        <label for="gender">
                            Gender
                        </label>

                        <select name="gender" id="gender">

                            <option value="">Select Gender</option>

                            <option value="Male"
                                <?php echo (($_POST['gender'] ?? '') === 'Male') ? 'selected' : ''; ?>>
                                Male
                            </option>

                            <option value="Female"
                                <?php echo (($_POST['gender'] ?? '') === 'Female') ? 'selected' : ''; ?>>
                                Female
                            </option>

                        </select>

                    </div>

                    <div class="form-group">

                        <label for="date_of_birth">
                            Date of Birth
                        </label>

                        <input
                            type="date"
                            id="date_of_birth"
                            name="date_of_birth"
                            value="<?php echo htmlspecialchars($_POST['date_of_birth'] ?? ''); ?>"
                        >

                    </div>

                </div>

            </div>


            <!-- CONTACT INFORMATION -->
            <div class="form-section">

                <h3>Contact Information</h3>

                <div class="form-grid">

                    <div class="form-group">

                        <label for="email">
                            Email Address *
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label for="phone">
                            Phone Number *
                        </label>

                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                            required
                        >

                    </div>

                    <div class="form-group full-width">

                        <label for="address">
                            Residential Address
                        </label>

                        <textarea
                            id="address"
                            name="address"
                            rows="3"
                        ><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>

                    </div>

                </div>

            </div>


            <!-- EDUCATIONAL INFORMATION -->
            <div class="form-section">

                <h3>Educational Information</h3>

                <div class="form-grid">

                    <div class="form-group">

                        <label for="highest_qualification">
                            Highest Qualification *
                        </label>

                        <input
                            type="text"
                            id="highest_qualification"
                            name="highest_qualification"
                            placeholder="e.g. WAEC, SSCE, OND, HND, B.Sc."
                            value="<?php echo htmlspecialchars($_POST['highest_qualification'] ?? ''); ?>"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label for="institution">
                            Institution Attended
                        </label>

                        <input
                            type="text"
                            id="institution"
                            name="institution"
                            value="<?php echo htmlspecialchars($_POST['institution'] ?? ''); ?>"
                        >

                    </div>

                </div>

            </div>


            <!-- CHURCH / MINISTRY INFORMATION -->
            <div class="form-section">

                <h3>Church & Ministry Information</h3>

                <div class="form-grid">

                    <div class="form-group">

                        <label for="church_name">
                            Current Church
                        </label>

                        <input
                            type="text"
                            id="church_name"
                            name="church_name"
                            value="<?php echo htmlspecialchars($_POST['church_name'] ?? ''); ?>"
                        >

                    </div>

                    <div class="form-group">

                        <label for="ministry_role">
                            Ministry Role
                        </label>

                        <input
                            type="text"
                            id="ministry_role"
                            name="ministry_role"
                            placeholder="e.g. Pastor, Teacher, Evangelist"
                            value="<?php echo htmlspecialchars($_POST['ministry_role'] ?? ''); ?>"
                        >

                    </div>

                    <div class="form-group">

                        <label for="years_in_ministry">
                            Years in Ministry
                        </label>

                        <input
                            type="text"
                            id="years_in_ministry"
                            name="years_in_ministry"
                            value="<?php echo htmlspecialchars($_POST['years_in_ministry'] ?? ''); ?>"
                        >

                    </div>

                </div>

            </div>


            <!-- APPLICATION STATEMENT -->
            <div class="form-section">

                <h3>Application Statement</h3>

                <div class="form-group">

                    <label for="reason">
                        Why do you want to study at Olusegun Theological Seminary? *
                    </label>

                    <textarea
                        id="reason"
                        name="reason"
                        rows="6"
                        required
                    ><?php echo htmlspecialchars($_POST['reason'] ?? ''); ?></textarea>

                </div>

            </div>


            <!-- DECLARATION -->
            <div class="form-section declaration-section">

                <h3>Declaration</h3>

                <label class="checkbox-label">

                    <input
                        type="checkbox"
                        name="declaration"
                        value="1"
                        required
                    >

                    <span>
                        I confirm that the information provided in this application
                        is accurate and complete to the best of my knowledge.
                    </span>

                </label>

            </div>


            <div class="form-submit">

                <button type="submit" class="btn btn-primary">
                    Submit Application
                </button>

            </div>

        </form>

    </div>
</section>

<style>

.application-intro {
    max-width: 800px;
    margin: 0 auto 40px;
    text-align: center;
}

.application-intro h2 {
    color: var(--primary-color);
    margin-bottom: 10px;
}

.application-form {
    max-width: 950px;
    margin: auto;
}

.form-section {
    background: var(--white);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 30px;
    margin-bottom: 25px;
}

.form-section h3 {
    color: var(--primary-color);
    margin-bottom: 25px;
    font-size: 22px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-color);
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 13px 14px;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    font-family: inherit;
    font-size: 15px;
    box-sizing: border-box;
}

.form-group textarea {
    resize: vertical;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary-color);
}

.declaration-section {
    background: var(--light-bg);
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    line-height: 1.6;
}

.checkbox-label input {
    margin-top: 5px;
}

.form-submit {
    text-align: center;
    margin-top: 30px;
}

.form-message {
    max-width: 950px;
    margin: 0 auto 25px;
    padding: 16px 20px;
    border-radius: 8px;
    font-weight: 600;
}

.success-message {
    background: #e8f7ee;
    color: #176b3a;
    border: 1px solid #b7e4c7;
}

.error-message {
    background: #fdecec;
    color: #a12626;
    border: 1px solid #f3bcbc;
}

@media (max-width: 700px) {

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full-width {
        grid-column: auto;
    }

    .form-section {
        padding: 20px;
    }

}

</style>

<?php include 'includes/footer.php'; ?>