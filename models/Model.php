<?php
abstract class Model {

    protected array $relations = [];

    
    public function __construct(array $data = []) {
        $allowed = get_object_vars($this);
        foreach ($allowed as $key => $_) {
            if (array_key_exists($key, $data)) {
                $this->$key = $data[$key];
            }
        }
    }

     public function __get($name) {
        if (method_exists($this, $name)) {
            if (!isset($this->relations[$name])) {
                $this->relations[$name] = $this->$name();
            }
            return $this->relations[$name];
        }

        trigger_error("Proprietà o relazione '$name' non definita nella classe " . get_class($this), E_USER_NOTICE);
        return null;
    }
}
