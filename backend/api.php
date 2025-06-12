<?php
require_once __DIR__ . "/../config/db.php";

header("Content-Type: application/json");

$action = $_GET["action"] ?? "";

switch ($action) {
    case "probables":
        $sql = "SELECT id, title, year, file_path, views, downloads, coming_soon FROM probables WHERE coming_soon = FALSE";
        $result = $conn->query($sql);
        $probables = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $probables[] = $row;
            }
        }
        echo json_encode($probables);
        break;

    case "study_materials":
        $type = $_GET["type"] ?? "written"; // 'written' or 'orals'
        $subject_function = $_GET["subject_function"] ?? "";
        $topic = $_GET["topic"] ?? "";
        $author = $_GET["author"] ?? "";
        $search = $_GET["search"] ?? "";

        $sql = "SELECT id, title, subject_function, topic, author, type, file_path, views, downloads, coming_soon FROM study_materials WHERE coming_soon = FALSE AND type = ?";
        $params = [$type];
        $types = "s";

        if (!empty($subject_function)) {
            $sql .= " AND subject_function = ?";
            $params[] = $subject_function;
            $types .= "s";
        }
        if (!empty($topic)) {
            $sql .= " AND topic LIKE ?";
            $params[] = "%" . $topic . "%";
            $types .= "s";
        }
        if (!empty($author)) {
            $sql .= " AND author = ?";
            $params[] = $author;
            $types .= "s";
        }
        if (!empty($search)) {
            $sql .= " AND (title LIKE ? OR topic LIKE ?)";
            $params[] = "%" . $search . "%";
            $params[] = "%" . $search . "%";
            $types .= "ss";
        }

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
            $materials = [];
            while($row = $result->fetch_assoc()) {
                $materials[] = $row;
            }
            echo json_encode($materials);
            $stmt->close();
        } else {
            echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
        }
        break;

    case "mock_tests":
        $sql = "SELECT id, test_type, questions, upload_path FROM mock_tests";
        $result = $conn->query($sql);
        $tests = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $tests[] = $row;
            }
        }
        echo json_encode($tests);
        break;

    case "update_view_count":
        $id = $_POST["id"] ?? 0;
        $table = $_POST["table"] ?? "";

        if ($id > 0 && ($table == "probables" || $table == "study_materials")) {
            $sql = "UPDATE " . $table . " SET views = views + 1 WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "message" => "Record not found or no update occurred."]);
            }
            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "Invalid parameters."]);
        }
        break;

    case "update_download_count":
        $id = $_POST["id"] ?? 0;
        $table = $_POST["table"] ?? "";

        if ($id > 0 && ($table == "probables" || $table == "study_materials")) {
            $sql = "UPDATE " . $table . " SET downloads = downloads + 1 WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "message" => "Record not found or no update occurred."]);
            }
            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "Invalid parameters."]);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid action."]);
        break;
}

$conn->close();
?>

