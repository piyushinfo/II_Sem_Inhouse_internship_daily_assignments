<?php
// 403.php doesn't need auth_check.php — it's the page you're
// redirected TO when you fail the check, so it must stay public.
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Access Denied</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow-sm text-center">
          <div class="card-body p-5">
            <div style="font-size: 4rem;">🔒</div>
            <h3 class="mt-3">Access Denied</h3>
            <p class="text-muted">
              You need to be logged in to view that page. Please sign in to continue.
            </p>
            <a href="login.php" class="btn btn-primary w-100">Back to Login</a>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
