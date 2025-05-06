<?php
abstract class Model {
    public function __construct(array $data = []) {
        $allowed = get_object_vars($this);
        foreach ($allowed as $key => $_) {
            if (array_key_exists($key, $data)) {
                $this->$key = $data[$key];
            }
        }
    }
}
