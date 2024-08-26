<?php

namespace Drupal\dependency_inject_services\services;

use Drupal\Core\Database\Connection;

class DatabaseInsert {

    protected $database;
    
    public function __construct(Connection $database){
      $this->database = $database;
    }

    /**
     *  Set Data function for inserting values to the database
     */
    public function setData($form_state){
      $this->database->insert('services_dependency')
      ->fields(array(
          'mail' => $form_state->getValue('mail'),
          'name' => $form_state->getValue('name'),
          'created' => time(),
          ))
          ->execute();
    }

    /**
     * get Data function for fetching data from the Database
     */
    public function getData(){
        $query = $this->database->select('services_dependency','cf');
        $query->fields('cf');
        $result = $query->execute()->fetchAll();
        return $result;
     }
}
