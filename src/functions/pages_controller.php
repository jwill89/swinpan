<?php

/**
 * Created by PhpStorm.
 * User: James
 * Date: 5/12/2017
 * Time: 11:04 PM
 */
class PagesController
{

    // Method for Each Page

    public function about() {
        include("content/about.php");
    }

    public function add() {
        include("content/add.php");
    }

    public function calendar() {
        include("content/calendar.php");
    }

    public function contact() {
        include("content/contact.php");
    }

    public function directory() {
        include("content/directory.php");
    }

    public function error() {
        include("content/error.php");
    }

    public function faq() {
        include("content/faq.php");
    }

    public function home() {
        include("content/home.php");
    }

    public function search() {
        include("content/search.php");
    }

    public function who() {
        include("content/who.php");
    }

}