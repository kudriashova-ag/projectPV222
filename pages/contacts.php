<h1>Contacts</h1>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clear($_POST['name'] ?? '');
    $email = clear($_POST['email'] ?? '');
    $message = clear($_POST['message'] ?? '');

    if(empty($name) || empty($email) || empty($message)){
        //echo "<div class='text-danger'>All fields are required</div>";
        $_SESSION['message'] = ['All fields are required', 'danger'];
    }
    else{
        mail("kudriashova.ag@gmail.com", "From contacts", "$name $email $message");
        //echo "<div class='text-success'>Thank!</div>";
        $_SESSION['message'] = ['Thank!', 'success'];
    }
    redirect('contacts');
}
?>


<?php
if(isset($_SESSION['message'])){
    list($text, $type) = $_SESSION['message'];
    echo "<div class='text-$type'>$text</div>";
    unset($_SESSION['message']);
}
?>



<form action="/contacts" method="POST">
    <div class="mb-3">
        <label for="form-label">Name:</label>
        <input type="text" name="name" class="form-control">
    </div>

    <div class="mb-3">
        <label for="form-label">Email:</label>
        <input type="text" name="email" class="form-control">
    </div>

    <div class="mb-3">
        <label for="form-label">Message:</label>
        <textarea class="form-control" name="message"></textarea>
    </div>

    <button class="btn btn-primary mt-3">Send</button>
</form>
