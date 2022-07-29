<?php
namespace Intervolga\Reviews;

class Review {
    public $id;
    public $name_creator;
    public $date_create;
    public $content;

    function __construct($id, $name_creator, $date_create, $content) {
        $this->id = $id;
        $this->name_creator = $name_creator;
        $this->date_create = $date_create;
        $this->content = $content;
    }
}