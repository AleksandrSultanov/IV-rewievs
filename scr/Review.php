<?php
namespace Intervolga\Reviews;

class Review {
    public $id;
    public $name_creator;
    public $date_create;
    public $content;

    function __construct($id, $date_create, $name_creator, $content) {
        $this->id = $id;
        $this->name_creator = $name_creator;
        $this->date_create = $date_create;
        $this->content = $content;
    }
}