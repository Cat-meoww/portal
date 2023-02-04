<!doctype html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <style>
        .v-100 {
            height: 100vh;
        }

        .w500 {
            width: 350px;
        }
    </style>
</head>

<body class="">


    <div class="wrapper">
        <section class="login-content">
            <div class="container d-flex justify-content-center align-items-center vh-100">
                <div class="row justify-content-center align-items-center height-self-center">
                    <div class="col-md-5 col-sm-12 col-12 align-self-center w500">
                        <div class="sign-user_card">

                            <h3 class="mb-3">Sign In</h3>
                            <p>Login to stay connected.</p>
                            <?php if (isset($validation)) : ?>

                                <?= $validation->listErrors('alert-info-list') ?>


                            <?php endif; ?>

                            <form action="<?= base_url('auth/login/check') ?>" method="post">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="floating-label form-group">
                                            <label>Email</label>
                                            <input class="floating-input form-control" name="email" type="email" placeholder=" ">

                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="floating-label form-group">
                                            <label>Password</label>
                                            <input class="floating-input form-control" name="password" type="password" placeholder=" ">

                                        </div>
                                    </div>


                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Sign In</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>


</html>