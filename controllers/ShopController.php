<?php

namespace main\controllers;

use app\db\MongoDatabase;
use app\views\Views;
use MongoDB\BSON\Regex;
use MongoDB\Driver\Cursor;
use MongoDB\Model\BSONDocument;

class ShopController
{
    private Views $views;
    protected MongoDatabase $db;

    public function __construct(Views $views)
    {
        $this->views = $views;
        $this->db = new MongoDatabase($_ENV["DB_NAME"], "shop");
    }

    public function get(): void
    {
        $this->views->render("shop", ["layout" => "main"]);
    }

    public function getBooks(array $options, array $search = []): bool|string
    {
        $returns = "";
        $result = $this->getBooksFromDB($search);
        $column = 0;
        $index = 0;
        $def = "Not specified";

        foreach ($result as $item) {
            $index += 1;
            $genreArray = explode("//", $item['genres']);
            $genres = implode(" ", $genreArray);
            $name = trim($item["book_name"]);
            $formattedName = str_replace([" ", "'", "\""], ["_", "", ""], $item["book_name"]);
            $img = $item["illustrations"][0] ?? "default.jpg";
            $volume = $item['volume'] === 0 ? "" : self::itemTemplate("Volume", $item['volume']);
            $seriesName = $item['series_name'] === "" ? "" : self::itemTemplate("Series Name", $item['series_name']);
            $stars = $this->generateStars($item['star_rating']);
            $publicationDate = $item['publication_date']->toDateTime()->format("d M Y") ?? $def;
            $publisher = $item['publisher'] ?? $def;
            $price = $item['price'] ?? $def;
            $author = $item['author'] ?? $def;
            $synopsis = $item['synopsis'] ?? $def;
            $images = self::generateImagesTemplate(iterator_to_array($item['illustrations']) ?? ['default.jpg']);
            $templateOfModal = "<div class=\"modal fade\" id=\"$formattedName\" data-bs-backdrop=\"static\" data-bs-keyboard=\"false\" tabindex=\"-1\" aria-labelledby=\"staticBackdropLabel\" aria-hidden=\"true\"> <div class=\"modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl text-left\"> <div class=\"modal-content bg-dark text-white\"> <div class=\"modal-header\"> <h5 class=\"modal-title\" id=\"staticBackdropLabel\">$name</h5> <button type=\"button\" class=\"btn-close btn-close-white\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button> </div> <div class=\"modal-body\"> $seriesName $volume <p class=\"item\"><span class=\"key\">Author:</span>$author</p> <p class=\"item\"><span class=\"key\">Publisher:</span>$publisher</p> <p class=\"item\"><span class=\"key\">Price:</span>$price</p> <p class=\"item\"><span class=\"key\">Publication Date:</span>$publicationDate</p> <p class=\"item\"><span class=\"key\">Genres:</span><span class='genres'> " . ucwords($genres) . "</span></p> <p class=\"item\"><span class=\"key\">Star rating:</span>$stars</p> <p class=\"item\"><span class=\"key synopsis-key\">Synopsis:</span></p> <p class=\"synopsis\">$synopsis</p> <p class=\"item\"><span class=\"key\">Illustrations: </span></p> <div class=\"horizontal-center\">$images</div> </div> <div class=\"modal-footer\"> <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Close</button> </div> </div> </div> </div>";
            echo $this->views->replaceRaw($templateOfModal);

            if ($column === 0) {
                $returns .= "<div class=\"productRow\">"; // Start of a row aka the first of three products
            }

            $returns .= "<article class=\"mix productInfo $genres\" id='$index'> <div><img alt=\"Image not Found.. :(\" src=\"{home}/static/images/books/$img\"></div> <p class=\"price\">$name</p> <button class=\"buyButton\" data-bs-toggle=\"modal\" data-bs-target=\"#$formattedName\">Details</button></article>";

            if ($column === 2) {
                $returns .= "</div>"; // Put at the end to close off after third product of the row and ensure items get echoed
                $column = 0;
                continue;
            }
            $column += 1;
        }

        return $this->views->replaceRaw($returns);
    }

    public function getBooksFromDB($find = [], $limit = 21): BSONDocument|Cursor|string|null
    {
        return $this->db->find([$find, ['limit' => $limit, 'sort' => ['index' => -1]]], false);
    }

    public static function itemTemplate(string $key, mixed $item): string
    {
        return "<p class=\"item\"><span class=\"key\">$key:</span>$item</p>";
    }

    /**
     * @param float|int $star_rating
     * Depends on the use of fontawesome icons or may be edited to suit
     * @return string
     */
    public function generateStars(float|int $star_rating): string
    {
        $solid = "<i class=\"fa-solid fa-star\"></i>";
        $half = "<i class=\"fa-solid fa-star-half\"></i>";
        $flooredStarRating = floor($star_rating);
        if ($flooredStarRating == $star_rating) {
            return str_repeat($solid, (int)$star_rating);
        }
        return str_repeat($solid, $flooredStarRating) . $half;
    }

    public static function generateImagesTemplate(array $images): string
    {
        $template = "<img src=\"{home}/static/images/books/{{imageName}}\" alt=\"Loading...\">";
        $returns = "";
        foreach ($images as $image) {
            $returns .= str_replace("{{imageName}}", $image, $template);
        }
        return $returns;
    }

    public function findBooks(): void
    {
        $searchStr = json_decode($_POST['data']);
        $pattern = ".*" . $searchStr . ".*";
        $regexSearchStr = new Regex($pattern, 'i');
        $result = $this->getBooks([], ['book_name' => $regexSearchStr]);

        if (empty($result)) {
            echo "Nothing is found :'( <br/> Try something else?";
        } else {
            echo $result;
        }
    }
}