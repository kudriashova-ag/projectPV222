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
    resizeImage($dir . '/' . $fName, 100, true, 'uploads-img/small');
    resizeImage($dir . '/' . $fName, 300, false, 'uploads-img/big');
    $_SESSION['message'] = ['File is uploaded', 'success'];
    //redirect('uploads');
}


function resizeImage(string $path, int $size, bool $crop, string $pathToSave)
{
    extract(pathinfo($path));
    $functionCreate = 'imagecreatefrom' . ($extension === 'jpg' ? 'jpeg' : $extension);
    $src = $functionCreate($path);
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
    $functionSave = 'image' . ($extension === 'jpg' ? 'jpeg' : $extension);
    if(!file_exists($pathToSave)){
        mkdir($pathToSave);
    }
    $functionSave($dest, "$pathToSave/$basename");
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


<?php 
// $files = scandir('uploads-img');
// $files = array_diff($files, ['.', '..']);
// foreach($files as $file){
//     if(!is_dir("uploads-img/$file")){
//         echo "<img src='uploads-img/$file' alt='$file' style='width: 200px'>";
//     }
// }

// dump($files);

/* $dir = opendir('uploads-img');
while($file = readdir($dir)){
    echo $file . '<br>';
}
closedir($dir); */
$files = glob(__DIR__ . '/../uploads-img/*{jpeg,jpg,gif,png,webp,avif}', GLOB_BRACE);
dump($files);
