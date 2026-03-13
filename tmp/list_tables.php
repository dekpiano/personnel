<?php
require_once dirname(__DIR__, 2) . '/app/Config/Database.php';
$db = \Config\Database::connect();
$query = $db->query("SHOW TABLES");
foreach ($query->getResult() as $row) {
    print_r($row);
}
