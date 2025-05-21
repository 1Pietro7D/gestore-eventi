<?php // php database/seeds/seeder_categorie.php

require_once __DIR__ . '/../Database.php';

class CategoriaSeeder {
    public function run() {
        $db = Database::connect();

        $stmt = $db->prepare("
            INSERT INTO categorie (nome)
            VALUES (:nome)
        ");

        $categorie = [
            [
                'nome' => 'Workshop',
                
            ],
            [
                'nome' => 'Corsi di cucina',
                
            ],
            [
                'nome' => 'Sviluppo personale',
                
            ],
            [
                'nome' => 'Informatica',
              
            ],
            [
                'nome' => 'Arte e cultura',
               
            ]
        ];

        foreach ($categorie as $categoria) {
            $stmt->execute($categoria);
        }

        echo "Categorie inserite con successo.\n";
    }
}

if (php_sapi_name() === 'cli') {
    (new CategoriaSeeder())->run();
}
