<?php


namespace Nextend\SmartSlider3Pro\Generator\Common;

use Nextend\Framework\Filesystem\Filesystem;
use ReflectionClass;
use ReflectionException;

class GeneratorCommonRESTLoader {

    public function __construct() {

        try {
            $reflectionClass = new ReflectionClass($this);
            $namespace       = $reflectionClass->getNamespaceName();

            $dir = dirname($reflectionClass->getFileName());

            foreach (Filesystem::folders($dir) as $name) {
                $className = '\\' . $namespace . '\\' . $name . '\\GeneratorGroupREST' . $name;

                if (class_exists($className)) {
                    new $className;
                }
            }
        } catch (ReflectionException $e) {

        }
    }
}