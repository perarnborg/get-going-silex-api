<?php
class ColorController {
  public static function index($app) {
    $colorsService = new Color($app);
    $colors = $colorsService->index();

    return Api::respond($colors);
  }

  public static function get($app, $id) {
    $colorsService = new Color($app);
    $color = $colorsService->get($id);

    if (!$color) {
       $app->abort(404, "id {$id} does not exist.");
    }
    return Api::respond($color);
  }
}
