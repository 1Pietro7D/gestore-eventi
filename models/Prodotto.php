<?php
abstract class Prodotto extends Model {
    public int $id = 0;
    public string $titolo = '';
    public float $prezzo = 0.0;

    abstract public function descrizione(): string;
    abstract public function urlSuccesso(): string;
    abstract public function urlAnnullato(): string;
}
