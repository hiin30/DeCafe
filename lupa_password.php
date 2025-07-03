<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password - DeCafe</title>
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="assets/css/login.css" rel="stylesheet">
</head>
<body class="text-center">

<main class="form-signin w-100 m-auto">
    <form class="needs-validation" novalidate action="proses/kirim_reset_link.php" method="POST">
        <i class="bi bi-lock fs-1"></i>
        <h1 class="h3 mb-3 fw-normal">Reset Password</h1>

        <div class="form-floating">
            <input type="email" name="email" class="form-control" id="floatingInput" placeholder="Email kamu" required>
            <label for="floatingInput">Masukkan Email Kamu</label>
            <div class="invalid-feedback">
                Mohon masukkan email yang valid.
            </div>
        </div>

        <button class="w-100 btn btn-lg btn-success mt-3" type="submit">Kirim Link Reset</button>

        <div class="mt-3">
            <a href="index.php" class="text-decoration-none">Kembali ke Login</a>
        </div>

        <p class="mt-5 mb-3 text-muted">&copy; 2023 - <?php echo date("Y"); ?></p>
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
