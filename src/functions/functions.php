<?php
/**
 * Created by PhpStorm.
 * User: James
 * Date: 5/13/2017
 * Time: 12:07 PM
 */

function call($controller, $action) {

    // Get our Controller
    require_once("pages_controller.php");

    // Determine our Controllers
    switch ($controller) {
        case 'pages':
            $controller = new PagesController();
            break;
    }

    // Display Page
    $controller->{$action}();

}

function getTags(): array {

    $db = DB::getInstance();

    $sql = "SELECT * FROM `tags` ORDER BY name";
    $sth = $db->query($sql,PDO::FETCH_OBJ);
    $tags = $sth->fetchAll();

    echo $tags;

    return $tags;

}

function getTagName(int $tag_id): string {

    $db = DB::getInstance();

    $sql = "SELECT name FROM `tags` WHERE `tag_id` = $tag_id";
    $result = $db->query($sql, PDO::FETCH_OBJ)->fetch();

    if ($result) {
        return $result->name;
    } else {
        return "Invalid Tag";
    }
}