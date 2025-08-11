<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
// Carichiamo le variabili d'ambiente dal file .env
$dotenv->load();

class CheckoutController extends BaseController{

    public function index() {
        
    $items = Cart::items();

    if (empty($items)) {
        FlashMessages::add('error', 'Il tuo carrello è vuoto.');
        redirect('cart.index');
    }
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        $lineItems = [];
        foreach (Cart::items() as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['prodotto']->titolo,
                    ],
                    'unit_amount' => $item['prodotto']->prezzo * 100, // Stripe utilizza centesimi
                ],
                'quantity' => $item['quantita'],
            ];
        }

        $session = Session::create([
            'payment_method_types' => ['card', 'paypal'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => 'http://localhost:8000/checkout/successo',
            'cancel_url' => 'http://localhost:8000/checkout/cancellato',
        ]);

        header("Location: {$session->url}");
        exit;
    }

    public function success() {
        Cart::clear();
        FlashMessages::add('success', 'Pagamento completato con successo!');
        redirect('home');
    }

    public function cancel() {
        FlashMessages::add('error', 'Pagamento annullato.');
        redirect('cart.index');
    }
}
