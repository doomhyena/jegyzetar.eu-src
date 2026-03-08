<?php

    require "db.php";
    require "functions.php";

    $userRes = db_query($conn, "SELECT * FROM users WHERE id = ? LIMIT 1", "i", [$_COOKIE['id']]);
    if (!$userRes || $userRes->num_rows === 0) {
        die("Felhasználó nem található.");
    }
    $user = $userRes->fetch_assoc();
    
    if (isset($_POST['file_id'])) {
        $file_id = (int)$_POST['file_id']; 
        
        $fileRes = db_query($conn, "SELECT * FROM files WHERE id = ? AND uploaded_by = ? LIMIT 1", "ii", [$file_id, $user['id']]);
        
        if ($fileRes && $fileRes->num_rows > 0) { 
            $file = $fileRes->fetch_assoc(); 
            $file_name = $file['file_name']; 
            
            $path = __DIR__ . "/../users/" . $user['username'] . "/" . $file_name;
            
            db_stmt($conn, "DELETE FROM files WHERE id = ? LIMIT 1", "i", [$file_id])->close();
            
            if (file_exists($path)) { 
                unlink($path); 
            } else {
                echo "A fájl nem található a szerveren.";
            }
            
            header('Location: ../../profile.php?user=' . urlencode($user['username']));
            exit;
            
        } else {
            echo "Nincs ilyen fájl vagy nincs jogosultságod törölni."; 
        }
    } else {
        echo "Nincs fájl kiválasztva."; 
    }

