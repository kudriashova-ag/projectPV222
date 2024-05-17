<h1>Reviews</h1>

<?php
// $text = file_get_contents('reviews.txt');
// echo $text;

// $rows = file('reviews.txt');
// dump($rows);
?>


<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clear($_POST['name'] ?? '');
    $review = clear($_POST['review'] ?? '');
    $created_at = time();

    //$newReview = ['name' => $name, 'review' => $review, 'created_at' => $created_at];

    // $reviews = file_exists('reviews.txt') ? json_decode(file_get_contents('reviews.txt'), true) : [];
    // $reviews[] = compact('name', 'review', 'created_at');

    $newReview = compact('name', 'review', 'created_at');

    $file = fopen('reviews.txt', 'a+');
    fwrite($file,  json_encode($newReview) . "\n");
    fclose($file);
}
?>


<form action="/reviews" method="post">

    <div class="mt-3">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" class="form-control" required>
    </div>

    <div class="mt-3">
        <label for="review">Review:</label>
        <textarea name="review" id="review" class="form-control" required></textarea>
    </div>

    <button class="btn btn-primary mt-3">Send</button>

</form>


<?php
    $reviews = file('reviews.txt');
    foreach($reviews as $item){
        $review = json_decode($item, true);
        echo "<div>{$review['name']} - {$review['review']} - " .date('d.m.Y H:i', $review['created_at']) . "</div>";
    }
?>