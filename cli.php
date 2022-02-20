<?php


use Admin\project\Comment;
use Admin\project\Newspaper;
use Admin\project\User;

require_once 'vendor/autoload.php';

switch ($argv[1]) {
    case 'user':
        echo new User;
        break;
    case 'post':
        echo new Newspaper();
        break;
    case 'comment':
        echo new Comment();
        break;

}