<?php

namespace Add\Classes;


interface ArgumentInterface{
    public function add(string $key, string $value):void;
    public function get(string $argument):?string;
    public function getArguments(): array;
}