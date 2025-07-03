<?php
// session_start();
if (!empty($_SESSION['username_decafe'])) {
    header('location:home');
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.104.2">
    <title>DeCafe - Aplikasi Pemesanan Makanan dan Minuman Cafe</title>
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/sign-in/">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .background-image {
            background-image: url('./assets/img/decafe.jpg');
        }
        /* [CSS lainnya tetap sama] */
    </style>

    <link href="assets/css/login.css" rel="stylesheet">
</head>

<body class="text-center">

    <main class="form-signin w-100 m-auto">
        <form class="needs-validation" novalidate action="proses/proses_register.php" method="POST">
            <i class="bi bi-cup-hot fs-1"></i>
            <h1 class="h3 mb-3 fw-normal">Create an Account</h1>

            <div class="form-floating">
                <input name="username" type="email" class="form-control" id="floatingInput"
                    placeholder="name@example.com" required>
                <label for="floatingInput">Email address</label>
                <div class="invalid-feedback">
                    Please enter a valid email.
                </div>
            </div>

            <div class="form-floating">
                <input name="nama" type="text" class="form-control" id="floatingName" placeholder="Full Name" required>
                <label for="floatingName">Full Name</label>
                <div class="invalid-feedback">
                    Please enter your full name.
                </div>
            </div>

            <div class="form-floating">
                <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password"
                    required>
                <label for="floatingPassword">Password</label>
                <div class="invalid-feedback">
                    Please enter a password.
                </div>
            </div>

            <div class="form-floating">
                <input name="confirm_password" type="password" class="form-control" id="floatingConfirmPassword" placeholder="Confirm Password"
                    required>
                <label for="floatingConfirmPassword">Confirm Password</label>
                <div class="invalid-feedback">
                    Please confirm your password.
                </div>
            </div>

            <!-- Input tersembunyi untuk level -->
            <div class="form-floating">
                <input type="hidden" name="level" value="5"> <!-- Level otomatis Customer -->
            </div>


            <button class="w-100 btn btn-lg btn-success" type="submit">Register</button>

<div class="mt-3">
    <a href="lupa_password.php" class="text-decoration-none">Lupa Password?</a>
</div>



            <div class="divider">
                <span>OR</span>
            </div>

            <a href="proses/proses_google_oauth.php" class="w-100 btn btn-google mt-2">
                <img src="./assets/img/google_logo.png" alt="Google" style="width: 50px; height: 50px; margin-right: 10px;">
                Sign up with Google
            </a>

            <p class="mt-5 mb-3 text-muted">&copy; 2023 - <?php echo date("Y") ?></p>
        </form>
    </main>

    <script>
        (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>

</html>
