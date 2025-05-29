<?php
session_start();
require 'dbcon.php';

$alert = isset($_SESSION['alert']) ? $_SESSION['alert'] : null;

if (!empty($alert)) {
  $title = isset($alert['title']) ? json_encode($alert['title']) : '"Notificación"';
  $message = isset($alert['message']) ? json_encode($alert['message']) : '""';
  $icon = isset($alert['icon']) ? json_encode($alert['icon']) : '"info"';

  echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: $title,
                    " . (!empty($alert['message']) ? "text: $message," : "") . "
                    icon: $icon,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Hacer algo si se confirma la alerta
                    }
                });
            });
        </script>";
  unset($_SESSION['alert']);
}

if (isset($_POST['email']) && isset($_POST['password'])) {
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $password = mysqli_real_escape_string($con, $_POST['password']);

  $query = "SELECT * FROM usuarios WHERE email='$email'";
  $result = mysqli_query($con, $query);

  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $hashed_password = $row['password'];

    if (password_verify($password, $hashed_password)) {
      $_SESSION['email'] = $email;
      $_SESSION['rol'] = $row['rol'];
      $nombre = mb_strtoupper($row['nombre'], 'UTF-8');
      $apellidop = mb_strtoupper($row['apellidop'], 'UTF-8');
      $_SESSION['alert'] = [
        'message' => 'Sesión iniciada correctamente',
        'title' => "BIENVENIDO $nombre $apellidop",
        'icon' => 'success'
      ];
      header("Location: dashboard.php");
      exit();
    } else {
      $_SESSION['alert'] = [
        'title' => 'CONTRASEÑA INCORRECTA',
        'icon' => 'error'
      ];
      header("Location: login.php");
      exit();
    }
  } else {
    $_SESSION['alert'] = [
      'message' => 'El correo ingresado no existe o es incorrecto, intenta nuevamente',
      'title' => 'USUARIO INCORRECTO',
      'icon' => 'error'
    ];
    header("Location: login.php");
    exit();
  }
}

if (isset($_SESSION['email'])) {
  $email = $_SESSION['email'];

  $query = "SELECT * FROM usuarios WHERE email = '$email'";
  $result = mysqli_query($con, $query);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $nombre = mb_strtoupper($row['nombre'], 'UTF-8');
    $apellidop = mb_strtoupper($row['apellidop'], 'UTF-8');
    $_SESSION['alert'] = [
      'message' => 'Ya tienes una sesión activa',
      'title' => "BIENVENIDO $nombre $apellidop",
      'icon' => 'success'
    ];
    header('Location: dashboard.php');
    exit();
  } else {
    $_SESSION['alert'] = [
      'message' => 'Inicia sesión con un usuario existente',
      'title' => 'USUARIO NO ENCONTRADO',
      'icon' => 'error'
    ];
    header('Location: login.php');
    exit();
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="css/styles.css">
  <link rel="shortcut icon" type="image/x-icon" href="images/ics.ico" />
  <title>Ingresar | Lysce</title>
</head>

<body class="loginBody">
  <?php include('menu.php'); ?>

  <div class="container-fluid">
    <div class="row justify-content-center align-items-center" style="margin-top: 60px;margin-bottom:70px;">
      <div class="col-11 col-md-4 text-center bg-light p-5" style="border-radius: 10px;">
        <h3><b>INGRESAR</b></h3>
        <p style="margin-bottom:15px;">Escribe tus credenciales</p>
        <form action="" method="post" class="mb-3">
          <div class="form-floating mb-3">
            <input type="email" class="form-control" id="floatingInput" name="email" placeholder="Correo" autocomplete="off">
            <label style="margin-left: 0px;" for="floatingInput">Correo</label>
          </div>
          <div class="form-floating mb-4">
            <input type="password" class="form-control" id="floatingInput" name="password" placeholder="Password" autocomplete="off">
            <label style="margin-left: 0px;" for="floatingInput">Contraseña</label>
          </div>
          <button type="submit" class="btn btn-danger p-2" style="width: 100%;color:#fff;">Ingresar</button>
        </form>
      </div>
    </div>
  </div>
  <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>

</body>

</html>