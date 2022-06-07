<?php

include("database.php");

$database = new Database();


if (!$database->prepare_tables()) {
    return false;
}

return true;