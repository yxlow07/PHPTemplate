<?php

namespace main\controllers;

use app\db\MongoDatabase;
use app\views\Views;

class ShopController
{
    private Views $views;

    public function __construct(Views $views)
    {
        $this->views = $views;
    }

    public function get(): void
    {
        $this->views->render("shop", ["layout" => "main"]);
    }

    public function getBooks(): void
    {
        $db = new MongoDatabase($_ENV["DB_NAME"], "shop");
        $returns = "";

        $result = $db->find([
            [],
            [
                'limit' => 21,
                'sort' => ['index' => -1]
            ]
        ], false);

        $column = 0;

        foreach ($result as $item) {
            $genres = implode(" ", explode("//", $item['genres']));
            $name = trim($item["book_name"]);
            $img = $item["illustrations"][0] ?? "default.jpg";

            if ($column === 0) {
                $returns .= "<div class=\"productRow\">"; // Start of a row aka the first of three products
            }

            $returns .= "<article class=\"mix productInfo $genres\"> <div><img alt=\"sample\" src=\"{home}/static/images/books/$img\"></div> <p class=\"price\">$name</p> <input class=\"buyButton\" name=\"button\" type=\"button\" value=\"Buy\"></article>";

            if ($column === 2) {
                $returns .= "</div>"; // Put at the end to close off after third product of the row and ensure items get echoed
                $column = 0;
                continue;
            }
            $column += 1;
        }

        echo $this->views->replaceRaw($returns);
    }
}