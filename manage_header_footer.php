<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Header & Footer</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<style>
body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        .main-content {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
            color: #333;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #007bff;
            color: white;
            margin-top: 40px;
            border-top: 1px solid #ddd;
        }

        footer p {
            margin: 0;
            font-size: 14px;
        }

        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }

        input[type="text"] {
            width: 70%;
            padding: 10px;
            margin: 20px 0;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
<body>
    <h1>Quản lý Header & Footer</h1>
    <h2>Quản lý Header Links</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>URL</th>
        <th>Position</th>
        <th>Logged In Only</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>
    <?php
    $header_links_query = "SELECT * FROM header_links ORDER BY position";
    $header_links_result = $conn->query($header_links_query);

    while($header = $header_links_result->fetch_assoc()) {
        echo "<tr>
                <td>{$header['id']}</td>
                <td>{$header['title']}</td>
                <td>{$header['url']}</td>
                <td>{$header['position']}</td>
                <td>" . ($header['is_logged_in'] ? 'Yes' : 'No') . "</td>
                <td>{$header['created_at']}</td>
                <td>
                    <a href='edit_header_link.php?id={$header['id']}'>Edit</a> |
                    <a href='delete_header_link.php?id={$header['id']}'>Delete</a>
                </td>
            </tr>";
    }
    ?>
</table>

<a href="add_header_link.php">Add New Header Link</a>
<h2>Quản lý Footer Sections</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Section Title</th>
        <th>Position</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>
    <?php
    $footer_sections_query = "SELECT * FROM footer_sections ORDER BY position";
    $footer_sections_result = $conn->query($footer_sections_query);

    while($section = $footer_sections_result->fetch_assoc()) {
        echo "<tr>
                <td>{$section['id']}</td>
                <td>{$section['section_title']}</td>
                <td>{$section['position']}</td>
                <td>{$section['created_at']}</td>
                <td>
                    <a href='edit_footer_section.php?id={$section['id']}'>Edit</a> |
                    <a href='delete_footer_section.php?id={$section['id']}'>Delete</a>
                </td>
            </tr>";
    }
    ?>
</table>

<a href="add_footer_section.php">Add New Footer Section</a>

<h2>Quản lý Footer Links</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Section ID</th>
        <th>Title</th>
        <th>URL</th>
        <th>Icon Class</th>
        <th>Position</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>
    <?php
    $footer_links_query = "SELECT * FROM footer_links ORDER BY position";
    $footer_links_result = $conn->query($footer_links_query);

    while($link = $footer_links_result->fetch_assoc()) {
        echo "<tr>
                <td>{$link['id']}</td>
                <td>{$link['section_id']}</td>
                <td>{$link['title']}</td>
                <td>{$link['url']}</td>
                <td>{$link['icon_class']}</td>
                <td>{$link['position']}</td>
                <td>{$link['created_at']}</td>
                <td>
                    <a href='edit_footer_link.php?id={$link['id']}'>Edit</a> |
                    <a href='delete_footer_link.php?id={$link['id']}'>Delete</a>
                </td>
            </tr>";
    }
    ?>
</table>

<a href="add_footer_link.php">Add New Footer Link</a>
