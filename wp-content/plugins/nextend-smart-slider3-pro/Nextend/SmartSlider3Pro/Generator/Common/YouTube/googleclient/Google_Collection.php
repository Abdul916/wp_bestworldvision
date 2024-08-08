<?php

namespace Nextend\SmartSlider3Pro\Generator\Common\YouTube\googleclient;

use Countable;
use Iterator;

/**
 * Extension to the regular Google_Model that automatically
 * exposes the items array for iteration, so you can just
 * iterate over the object rather than a reference inside.
 */
class Google_Collection extends Google_Model implements Iterator, Countable {

    protected $collection_key = 'items';

    public function rewind(): void {
        if (isset($this->modelData[$this->collection_key]) && is_array($this->modelData[$this->collection_key])) {
            reset($this->modelData[$this->collection_key]);
        }
    }

    /** @return mixed */
    #[\ReturnTypeWillChange]
    public function current() {
        $this->coerceType($this->key());
        if (is_array($this->modelData[$this->collection_key])) {
            return current($this->modelData[$this->collection_key]);
        }
    }

    /** @return mixed */
    #[\ReturnTypeWillChange]
    public function key() {
        if (isset($this->modelData[$this->collection_key]) && is_array($this->modelData[$this->collection_key])) {
            return key($this->modelData[$this->collection_key]);
        }
    }

    public function next(): void {
        next($this->modelData[$this->collection_key]);
    }

    public function valid(): bool {
        $key = $this->key();

        return $key !== null && $key !== false;
    }

    public function count(): int {
        return count($this->modelData[$this->collection_key]);
    }

    public function offsetExists($offset): bool {
        if (!is_numeric($offset)) {
            return parent::offsetExists($offset);
        }

        return isset($this->modelData[$this->collection_key][$offset]);
    }

    /** @return mixed */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset) {
        if (!is_numeric($offset)) {
            return parent::offsetGet($offset);
        }
        $this->coerceType($offset);

        return $this->modelData[$this->collection_key][$offset];
    }

    public function offsetSet($offset, $value): void {
        if (!is_numeric($offset)) {
            parent::offsetSet($offset, $value);
        }
        $this->modelData[$this->collection_key][$offset] = $value;
    }

    public function offsetUnset($offset): void {
        if (!is_numeric($offset)) {
            parent::offsetUnset($offset);
        }
        unset($this->modelData[$this->collection_key][$offset]);
    }

    private function coerceType($offset) {
        $typeKey = $this->keyType($this->collection_key);
        if (isset($this->$typeKey) && !is_object($this->modelData[$this->collection_key][$offset])) {
            $type                                            = $this->$typeKey;
            $this->modelData[$this->collection_key][$offset] = new $type($this->modelData[$this->collection_key][$offset]);
        }
    }
}
