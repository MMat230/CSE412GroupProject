<?php include($_SERVER['DOCUMENT_ROOT'] . '/library/includes/header.php');

$display_message = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    // Set our vars
    $vars = $_POST;

    // Error Array
    $errors = array();

    // Super basic validation - check if each var exists
    $errors['Email']            = validate_exists($vars['email']);
    $errors['Password']         = validate_exists($vars['password']);

    if($vars['email'] && $vars['password']){
        $pdo = db_connect();
        $sql = 'SELECT * FROM users WHERE email = :email';
        $request = $pdo->prepare($sql);
        $request->execute(array('email' => $vars['email']));

        if($request->rowCount() > 0){
            // We have a user with that email
            $user = $request->fetch();

            if($user['password'] != md5($vars['password'])){
                $errors['Password'] = ' does not match';
            }
        }
    }

    if(has_errors($errors)){
        $display_message = '<p class="alert alert-danger">' . build_errors($errors) . '</p>';
    }else{
        // Validated, login user & set display message

//        Todo: Login user here
        $display_message = '<p class="alert alert-success">You have been successfully logged in!</p>';
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Login</h1>
            <?=$display_message; ?>
        </div>
        <div class="col-md-offset-3 col-md-6">
            <form role="form" method="post" action="">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/library/includes/footer.php'); ?>