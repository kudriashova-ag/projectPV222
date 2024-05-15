<h1>Uploads</h1>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_FILES['file'];
    extract($file);
    if ($error === 4) {
        $_SESSION['message'] = ['File is required', 'danger'];
        redirect('uploads');
    }
    if ($error !== 0) {
        $_SESSION['message'] = ['File is not uploaded', 'danger'];
        redirect('uploads');
    }
    $allowsTypes = ['image/gif', 'image/png', 'image/jpeg', 'image/webp', 'image/avif'];
    if (!in_array($type, $allowsTypes)) {
        $_SESSION['message'] = ['File is not image', 'danger'];
        redirect('uploads');
    }
    $fName = uniqid() . '_' . $name;
    $dir = "uploads-img";

    if (!file_exists($dir)) {
        mkdir($dir);
    }

    move_uploaded_file($tmp_name, $dir . '/' . $fName);
    resizeImage($dir . '/' . $fName, 100, true);
    resizeImage($dir . '/' . $fName, 300, false);
    $_SESSION['message'] = ['File is uploaded', 'success'];
    //redirect('uploads');
}


function resizeImage(string $path, int $size, bool $crop)
{
    $src = imagecreatefromjpeg($path);
    list($src_width, $src_height) = getimagesize($path);

    if($crop){
        // жорстка обрізка
        $dest = imagecreatetruecolor($size, $size);

        if($src_width > $src_height){
            imagecopyresampled($dest, $src, 0, 0, round($src_width / 2 - $src_height / 2), 0, $size, $size, $src_height, $src_height);
        }
        else{
            imagecopyresampled($dest, $src, 0, 0, 0, round($src_height / 2 - $src_width / 2), $size, $size, $src_width, $src_width);
        }
    }
    else{
        // пропорційне змінення
        $dest_width = $size;
        $dest_height = round($size * $src_height / $src_width);
        $dest = imagecreatetruecolor($dest_width, $dest_height);
        imagecopyresampled($dest, $src, 0, 0, 0, 0,$dest_width, $dest_height, $src_width, $src_height);
    }

    imagejpeg($dest, 'uploads-img/1.jpg', 100);
}
?>


<?php
if (isset($_SESSION['message'])) {
    list($text, $type) = $_SESSION['message'];
    echo "<div class='text-$type'>$text</div>";
    unset($_SESSION['message']);
}
?>

<form action="/uploads" method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <button>Submit</button>
</form>


<!-- 

Array
(
    [name] => js.jpg
    [full_path] => js.jpg
    [type] => image/jpeg
    [tmp_name] => C:\OSPanel\userdata\temp\upload\php673C.tmp
    [error] => 0
    [size] => 7915
)

 -->