<?php
use PHPUnit\Framework\TestCase;
//echo "PATH: " . __DIR__ . '/../core/Router.php' . PHP_EOL; die;

require __DIR__ . "/../../core/Router.php";
//require 'C:/laragon/www/gestore-eventi/core/Router.php';
//require realpath(__DIR__ . '/../core/Router.php');

/**
 * Classe di test per la Router
 */
class RouterTest extends TestCase
{
    private string $controllersPath = __DIR__ . '/../../controllers/';


    /**
     * setUp() viene eseguito prima di ogni test:
     * - crea la cartella dei controller se non esiste
     * - aggiunge un controller di test (TestController.php)
     */
    protected function setUp(): void
    {
        // $this->controllersPath = __DIR__ . '/../controllers/';

        // if (!file_exists($this->controllersPath)) {
        //     mkdir($this->controllersPath, 0777, true);
        // }

        // Crea un controller di base che ha due metodi:
        // - index() ? stampa "OK"
        // - show($id, $req) ? stampa "User: ID"
        file_put_contents(
            $this->controllersPath . 'TestController.php',
            '<?php class TestController { 
                public function index($req) { echo "OK"; } 
                public function show($id, $req) { echo "User: $id"; } 
            }'
        );
    }

    /**
     * tearDown() viene eseguito dopo ogni test:
     * - cancella il file controller creato durante i test
     */
    protected function tearDown(): void
    {
        unlink($this->controllersPath . 'TestController.php');
    }

    /**
     * Test che verifica la gestione di una route semplice (senza parametri dinamici)
     * Esempio: GET /dashboard
     */
    public function testRouteMatchWithoutParams()
    {
        $routes = [
            ['method' => 'GET', 'url' => '/dashboard', 'handler' => 'TestController@index']
        ];
        $router = new Router($routes);

        // Cattura l'output della chiamata (visto che usa echo)
        ob_start();
        $router->handle('GET', '/dashboard');
        $output = ob_get_clean();

        $this->assertStringContainsString('OK', $output);
    }

    /**
     * Test per route con parametri dinamici
     * Esempio: GET /users/42 deve catturare "42" e passarlo al controller
     */
    public function testRouteMatchWithParams()
    {
        $routes = [
            ['method' => 'GET', 'url' => '/users/{id}', 'handler' => 'TestController@show']
        ];
        $router = new Router($routes);

        ob_start();
        $router->handle('GET', '/users/42');
        $output = ob_get_clean();

        $this->assertStringContainsString('User: 42', $output);
    }

    /**
     * Test per verificare il comportamento quando una route richiede autorizzazione
     * ma l'utente NON è loggato ? deve restituire 401
     */
    public function testUnauthorizedAccess()
    {
        $routes = [
            [
                'method' => 'GET',
                'url' => '/private',
                'handler' => 'TestController@index',
                'authorized' => true
            ]
        ];
        $router = new Router($routes);

        unset($_SESSION); // Nessun utente loggato

        ob_start();
        $router->handle('GET', '/private');
        $output = ob_get_clean();

        $this->assertStringContainsString('Non autorizzato', $output);
        $this->assertSame(401, http_response_code());
    }

    /**
     * Test per verificare il comportamento quando l'utente è loggato
     * ma non ha il ruolo richiesto ? deve restituire 403
     */
    public function testForbiddenAccessDueToRole()
    {
        $routes = [
            [
                'method' => 'GET',
                'url' => '/admin',
                'handler' => 'TestController@index',
                'authorized' => true,
                'role' => ['admin']
            ]
        ];
        $router = new Router($routes);

        $_SESSION['user'] = ['ruolo' => 'user']; // Utente non admin

        ob_start();
        $router->handle('GET', '/admin');
        $output = ob_get_clean();

        $this->assertStringContainsString('Accesso negato', $output);
        $this->assertSame(403, http_response_code());
    }

    /**
     * Test per il caso in cui il file del controller NON esiste
     * Deve restituire 500 e stampare un messaggio di errore
     */
    public function testControllerFileNotFound()
    {
        $routes = [
            ['method' => 'GET', 'url' => '/missing', 'handler' => 'MissingController@index']
        ];
        $router = new Router($routes);

        ob_start();
        $router->handle('GET', '/missing');
        $output = ob_get_clean();

        $this->assertStringContainsString('Controller file not found', $output);
        $this->assertSame(500, http_response_code());
    }

    /**
     * Test per il caso in cui il file controller esiste,
     * ma la classe dichiarata NON corrisponde al nome atteso
     */
    public function testClassNotFoundInController()
    {
        // Crea un file controller ma con nome di classe sbagliato
        file_put_contents(
            $this->controllersPath . 'WrongController.php',
            '<?php class WrongName {}'
        );

        $routes = [
            ['method' => 'GET', 'url' => '/wrongclass', 'handler' => 'WrongController@index']
        ];
        $router = new Router($routes);

        ob_start();
        $router->handle('GET', '/wrongclass');
        $output = ob_get_clean();

        $this->assertStringContainsString('Class WrongController not found', $output);
        $this->assertSame(500, http_response_code());

        unlink($this->controllersPath . 'WrongController.php');
    }

    /**
     * Test per il caso in cui il metodo specificato nella route NON esiste nel controller
     * Deve restituire errore 500
     */
    public function testMethodNotFoundInController()
    {
        $routes = [
            ['method' => 'GET', 'url' => '/missingmethod', 'handler' => 'TestController@notFound']
        ];
        $router = new Router($routes);

        ob_start();
        $router->handle('GET', '/missingmethod');
        $output = ob_get_clean();

        $this->assertStringContainsString('Method notFound not found', $output);
        $this->assertSame(500, http_response_code());
    }

    /**
     * Test per il caso in cui NESSUNA rotta corrisponde all'URI richiesto
     * Deve restituire errore 404
     */
    public function testNotFoundRoute()
    {
        $routes = [
            ['method' => 'GET', 'url' => '/something', 'handler' => 'TestController@index']
        ];
        $router = new Router($routes);

        ob_start();
        $router->handle('GET', '/nonexistent');
        $output = ob_get_clean();

        $this->assertSame(404, http_response_code());
    }
}
