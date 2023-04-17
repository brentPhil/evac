<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Evacuation Center Management System</title>
      <!-- Google Font: Source Sans Pro -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="../asset/fontawesome/css/all.min.css">
      <!-- Theme style -->
      <link rel="stylesheet" href="../asset/css/adminlte.min.css">
   </head>
   <style>
      body {
        background: linear-gradient(rgba(0,0,0,0.75),rgba(0,0,0,0.75)), url(back.jpg) no-repeat center center fixed;
          width: 100vw;
          height: 100vh;
          -webkit-background-size: cover;
          -moz-background-size: cover;
          -o-background-size: cover;
          background-size: cover;
      }
   </style>
   <body class="hold-transition login-page">
      <div class="login-box">
         <!-- /.login-logo -->
         <div class="card card-outline">
            <div class="card-header text-center py-4">
               <img src="../asset/img/MDRRMO logo.png" alt="DSMS Logo" width="200">
            </div>
            <div class="card-body" >
               <form action="login_me.php" method="post">
                  <div class="input-group mb-3">
                     <input type="text" class="form-control" name="username" placeholder="Enter your email address">
                     <div class="input-group-append">
                        <div class="input-group-text">
                           <span class="fas fa-user"></span>
                        </div>
                     </div>
                  </div>
                  <div class="input-group mb-3">
                     <input type="password" class="form-control" name="password" placeholder="Password">
                     <div class="input-group-append">
                        <div class="input-group-text">
                           <span class="fas fa-lock"></span>
                        </div>
                     </div>
                  </div>
                     <div class="d-flex justify-content-center">
                        <button type="submit" name="login" class="btn btn-primary w-100" style="background-color: rgb(31,108,163);">Sign In</button>
                     </div>
               </form>
            </div>
            <!-- /.card-body -->
         </div>
         <!-- /.card -->
      </div>
      <!-- /.login-box -->
   </body>
</html>
