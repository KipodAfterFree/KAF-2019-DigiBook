<?php

include_once "../base/api.php";

const DB_FILE = "../../../files/database.json";
const BOOK_FILE = "../../../files/book.txt";

$database = json_decode(file_get_contents(DB_FILE));
$book = str_split(file_get_contents(BOOK_FILE), 1000);

api("digibook", function ($action, $parameters) {
    global $database, $book;
    if (!isset($database->{client_ip()})) {
        $object = new stdClass();
        $object->ids = new stdClass();
        $object->page = 0;
        $database->{client_ip()} = $object;
        save();
    }
    if (isset($parameters->id)) {
        if (isset($database->{client_ip()}->ids->{$parameters->id})) {
            if ($action === "read") {
                return [true, $book[$database->{client_ip()}->ids->{$parameters->id}->page]];
            } else if ($action === "next") {
                if ($database->{client_ip()}->ids->{$parameters->id}->page + 1 < $database->{client_ip()}->page) {
                    $database->{client_ip()}->ids->{$parameters->id}->page++;
                } else {
                    if (count($book) < $database->{client_ip()}->ids->{$parameters->id}->page + 1) {
                        return [false, "End of book"];
                    }
                    if ($database->{client_ip()}->ids->{$parameters->id}->time < time()) {
                        $database->{client_ip()}->ids->{$parameters->id}->time = time() + 60;
                        $database->{client_ip()}->ids->{$parameters->id}->page++;
                        $database->{client_ip()}->page++;
                    } else {
                        return [false, "Unable to next yet"];
                    }
                }
                save();
                return [true, "Page " . $database->{client_ip()}->ids->{$parameters->id}->page . " of " . count($book)];
            } else if ($action === "previous") {
                if ($database->{client_ip()}->ids->{$parameters->id}->page > 0) {
                    $database->{client_ip()}->ids->{$parameters->id}->page--;
                } else {
                    return [false, "Page 0"];
                }
                save();
                return [true, "Page " . $database->{client_ip()}->ids->{$parameters->id}->page . " of " . count($book)];
            }
        } else {
            return [false, "ID not found at this household"];
        }
    } else {
        $id = random(16);
        $database->{client_ip()}->ids->$id = new stdClass();
        $database->{client_ip()}->ids->$id->time = 0;
        $database->{client_ip()}->ids->$id->page = 0;
        save();
        return [true, $id];
    }
    return [false, "UK"];
});

echo json_encode($result);

function client_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function save()
{
    global $database;
    file_put_contents(DB_FILE, json_encode($database));
}