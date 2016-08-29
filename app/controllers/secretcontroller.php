<?php
class SecretController {
  public static function indexForUser($app) {
    $secretsService = new Secret($app);
    $secrets = $secretsService->indexForUser(UserService::current($app)->id);

    return Api::respond($secrets);
  }

  public static function getForUser($app, $id) {
    $secretsService = new Secret($app);
    $secret = $secretsService->getForUser(UserService::current($app)->id, $id);

    if (!$secret) {
       $app->abort(404, "id {$id} does not exist.");
    }
    return Api::respond($secret);
  }

  public static function create($app, $postData) {
    try {
      $secretsService = new Secret($app);
      $row = $secretsService->getRow($postData, UserService::current($app));
      $secret = $secretsService->save($row);

      return Api::respond($secret);
    } catch(Exception $ex) {
      return Api::error($app, $ex);
    }
  }

  public static function update($app, $id, $postData) {
    try {
      $secretsService = new Secret($app);
      $row = $secretsService->getRow($postData, UserService::current($app), $id);
      $row = $secretsService->save($row);

      return Api::respond($row);
    } catch(Exception $ex) {
      return Api::error($app, $ex);
    }
  }

  public static function delete($app, $id) {
    try {
      $secretsService = new Secret($app);
      $res = $secretsService->delete(UserService::current($app), $id);

      return Api::respond($res);
    } catch(Exception $ex) {
      return Api::error($app, $ex);
    }
  }
}
