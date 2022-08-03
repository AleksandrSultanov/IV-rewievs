<?php
namespace Intervolga\Reviews;

class Review {
    function __construct(public int $id, public string $name_creator, public string $date_create, public string $content) {}
}