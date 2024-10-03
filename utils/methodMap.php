<?php

function methodMap($method, $handler) {
    if ($_SERVER['REQUEST_METHOD'] === $method) {
        $handler();
    }
}