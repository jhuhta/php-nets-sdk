<?php

namespace NetsSdk;

/**
 * A trait containing reusable code for making arrays out of objects.
 */
trait ArrayRepresentationTrait {

  /**
   * Returns this object as an array representation.
   *
   * @return array
   *   The array representation of this object.
   */
  public function asArray() {
    try {
      $reflectionClass = new \ReflectionClass(get_class($this));
    }
    catch (\ReflectionException $e) {
      // Fall back to this hack. It doesn't handle protected properties, though.
      return json_decode(json_encode($this), TRUE);
    }
    $array = [];
    foreach ($reflectionClass->getProperties() as $property) {
      if ($property->isPrivate()) {
        continue;
      }
      $property->setAccessible(TRUE);
      $array[$property->getName()] = $property->getValue($this);
      $property->setAccessible(FALSE);
    }
    return $array;
  }

}
