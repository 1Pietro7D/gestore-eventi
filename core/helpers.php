<?php
function view($path, $data = []) {
    extract($data); // rende disponibili le variabili
    require "views/" . str_replace('.', '/', $path) . ".php";
}