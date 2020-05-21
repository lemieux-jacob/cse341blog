<?php
namespace Core;

use \PDO;

class DB {
  // Simple Class to Create a new PDO Object
  public static function connect() {
    $dbUrl = getenv('DATABASE_URL');
    
    if (empty($dbUrl)) {
      // Use Local DB
      $user = 'postgres';
      $password = 'Secret#01';
      $connection = 'pgsql:host=localhost;dbname=myBlog';
    } else {
      // Use Remote DB
      $dbOpts = parse_url($dbUrl);
      
      $dbHost = $dbOpts['host'];
      $dbPort = $dbOpts['port'];
      $dbUser = $dbOpts['user'];
      $dbPassword = $dbOpts['pass'];
      $dbName = ltrim($dbOpts['path'], '/');
  
      $connection = "pgsql:host=$dbHost;port=$dbPort;dbname=$dbName";
    }
    // Attempt Connection
    try {
      $db = new PDO($connection, $user, $password);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $ex) {
      dd('Error!:' . $ex->getMessage());
    }
  
    return $db;
  }
}