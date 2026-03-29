<?php
    header("Content-Type: application/json");

    $API_KEY = "idejonmajdazAPIkulcs";

    if ($_GET['key'] !== $API_KEY) {
        http_response_code(403);
        echo json_encode(["error" => "unauthorized"]);
        exit;
    }

    $conn = new mysqli("localhost", "root", "", "adatbazis");

    if ($_GET['action'] === 'get') {
        $res = $conn->query("SELECT * FROM tabla");
        echo json_encode($res->fetch_all(MYSQLI_ASSOC));
    }

    if ($_GET['action'] === 'update') {
        $conn->query("UPDATE tabla SET nev='teszt' WHERE id=1");
        echo json_encode(["status" => "ok"]);
    }
    