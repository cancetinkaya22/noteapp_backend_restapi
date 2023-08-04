<?php
require_once('DatabaseConfig.php');


$connection = mysqli_connect($host, $username, $password, $dbName);


if (!$connection) {
    die("Veritabanı bağlantısı başarısız: " . mysqli_connect_error());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $image_url = saveImage($image); 
    } else {
        $image_url =null; 
    }
    
    
    $query = "INSERT INTO notes (title, content, image_url) VALUES ('$title', '$content', '$image_url')";
    $result = mysqli_query($connection, $query);
    
    
    if ($result) {
        echo "Not başarıyla kaydedildi.";
    } else {
        echo "Not kaydedilirken bir hata oluştu: " . mysqli_error($connection);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    $query = "SELECT * FROM notes ORDER BY created_at DESC";
    $result = mysqli_query($connection, $query);
    
    
    if ($result) {
        $notes = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $note = array(
                "id" => $row['id'], 
                "title" => $row['title'],
                "content" => $row['content'],
                "image_url" => $row['image_url'] 
            );
            $notes[] = $note;
        }
        
        header('Content-Type: application/json');
        
        echo json_encode($notes);
    } else {
        echo "Notlar alınırken bir hata oluştu: " . mysqli_error($connection);
    }
}
 elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Gelen veriyi al
    $id = (int)$_GET['id']; 
    $putData = json_decode(file_get_contents("php://input"), true);
    $title = $putData['title'];
    $content = $putData['content'];

    
    $query = "UPDATE notes SET title = '$title', content = '$content' WHERE id = '$id'";
    $result = mysqli_query($connection, $query);

    
    if ($result) {
        echo "Not başarıyla güncellendi.";
    } else {
        echo "Not güncellenirken bir hata oluştu: " . mysqli_error($connection);
    }
}

 elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    
    $id = (int)$_GET['id']; 

    
    $query = "DELETE FROM notes WHERE id = '$id'";
    $result = mysqli_query($connection, $query);
    
  
    if ($result) {
        echo "Not başarıyla silindi.";
    } else {
        echo "Not silinirken bir hata oluştu: " . mysqli_error($connection);
    }
}
function saveImage($image) {
    
    $tmpFilePath = $image['tmp_name'];
    
   
    $targetDirectory = 'C:/wamp64/www/noteapi/images/'; 
    
    
    $fileName = uniqid() . '_' . $image['name'];
    
    
    $targetFilePath = $targetDirectory . $fileName;
    
  
    if (move_uploaded_file($tmpFilePath, $targetFilePath)) {
        
        return $targetFilePath; 
    } else {
        
        return ""; 
    }
}




mysqli_close($connection);
?>
