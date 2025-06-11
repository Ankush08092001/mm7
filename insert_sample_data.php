<?php
require_once __DIR__ . "/config/db.php";

// Insert sample probables
$probables_data = [
    ["MEO Class 4 Probables 2024", 2024, "sample_probables_2024.pdf", 0, 0, 0],
    ["MEO Class 4 Probables 2023", 2023, "sample_probables_2023.pdf", 15, 8, 0],
    ["MEO Class 4 Probables 2022", 2022, "sample_probables_2022.pdf", 25, 12, 0],
    ["MEO Class 4 Probables 2021", 2021, "sample_probables_2021.pdf", 18, 9, 0],
];

$stmt = $conn->prepare("INSERT INTO probables (title, year, file_path, views, downloads, coming_soon) VALUES (?, ?, ?, ?, ?, ?)");
foreach ($probables_data as $data) {
    $stmt->bind_param("sisiii", $data[0], $data[1], $data[2], $data[3], $data[4], $data[5]);
    $stmt->execute();
}
$stmt->close();

// Insert sample study materials
$study_materials_data = [
    ["Marine Diesel Engine Fundamentals", "MEK-G", "Engine Basics", "MarineMonks", "written", "mek_g_engine_fundamentals.pdf", 45, 23, 0],
    ["Ship Stability and Construction", "NAVAL", "Stability", "Samraj", "written", "naval_stability.pdf", 38, 19, 0],
    ["Electrical Systems on Ships", "MEP", "Electrical", "Dieselship", "written", "mep_electrical.pdf", 32, 16, 0],
    ["Function 3 Oral Questions", "Function 3", "Emergency Situations", "MarineMonks", "orals", "function3_orals.pdf", 28, 14, 0],
    ["Function 4B Engineering Questions", "Function 4B", "System Failures", "Ankush Notes", "orals", "function4b_orals.pdf", 35, 18, 0],
    ["Turbocharger Maintenance", "MEK-M", "Maintenance", "MarineMonks", "written", "turbocharger_maintenance.pdf", 0, 0, 1],
];

$stmt = $conn->prepare("INSERT INTO study_materials (title, subject_function, topic, author, type, file_path, views, downloads, coming_soon) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($study_materials_data as $data) {
    $stmt->bind_param("ssssssiii", $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8]);
    $stmt->execute();
}
$stmt->close();

// Insert sample mock tests
$mock_tests_data = [
    ["easy", "9 questions covering basic concepts"],
    ["medium", "9 questions with moderate difficulty"],
    ["hard", "9 challenging questions"],
];

$stmt = $conn->prepare("INSERT INTO mock_tests (test_type, questions) VALUES (?, ?)");
foreach ($mock_tests_data as $data) {
    $stmt->bind_param("ss", $data[0], $data[1]);
    $stmt->execute();
}
$stmt->close();

// Insert sample admin user
$admin_password = password_hash("admin123", PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, password_hash, is_premium_member) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE username=username");
$stmt->bind_param("ssi", $admin_username, $admin_password, $is_premium);
$admin_username = "admin";
$is_premium = 1;
$stmt->execute();
$stmt->close();

// Insert sample regular user
$user_password = password_hash("user123", PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, password_hash, is_premium_member) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE username=username");
$stmt->bind_param("ssi", $user_username, $user_password, $is_premium_user);
$user_username = "testuser";
$is_premium_user = 0;
$stmt->execute();
$stmt->close();

// Insert sample premium user
$premium_password = password_hash("premium123", PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, password_hash, is_premium_member) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE username=username");
$stmt->bind_param("ssi", $premium_username, $premium_password, $is_premium_premium);
$premium_username = "premiumuser";
$is_premium_premium = 1;
$stmt->execute();
$stmt->close();

$conn->close();

echo "Sample data inserted successfully!\n";
echo "Admin user: admin / admin123\n";
echo "Regular user: testuser / user123\n";
echo "Premium user: premiumuser / premium123\n";
?>

