<?php

namespace ContentCoffee\EntitySyncer;

/**
 * Class EntitySyncer
 *
 * Goal of this EntitySyncer service class is to sync all PUBLIC properties of
 * a given Entity with its companion's values.
 * The end result of this process is that $entity contains all values that where
 * available in $companion.
 * Typically this is used to sync Entities with their corresponding DTO classes.
 * If you want to shield a property from being synced, simply make them protected
 * or private.
 *
 */
class EntitySyncer {

  /**
   * @var object object that we want updated with the $companion's values.
   */
  protected $entity;

  /**
   * @var object object that will provide values for $entity.
   */
  protected $companion;

  public function __construct($entity = null, $companion = null) {
    if (!is_null($entity)) {
      $this->entity = $entity;
    }
    if (!is_null($companion)) {
      $this->companion = $companion;
    }
  }

  /**
   * @return object $entity
   */
  public function getEntity() {
    return $this->entity;
  }

  /**
   * @param object $entity
   */
  public function setEntity($entity) {
    $this->entity = $entity;
  }

  /**
   * @return object $companion
   */
  public function getCompanion() {
    return $this->companion;
  }

  /**
   * @param object $companion
   */
  public function setCompanion($companion) {
    $this->companion = $companion;
  }

  /**
   * @return object $entity
   */
  public function sync() {
    $companionMethods = get_class_methods($this->companion);
    $entityMethods = get_class_methods($this->entity);
    $methods = array_intersect($companionMethods, $entityMethods);

    foreach ($methods as $setter) {
      if (strpos($setter, 'set') !== FALSE) {
        $getter = str_replace('set', 'get', $setter);
        if (in_array($getter, $methods)) {
          $this->entity->$setter($this->companion->$getter());
        }
      }
    }

    return $this->entity;
  }
}
