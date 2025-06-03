<?php
class CartController {

    public function index() {
        return view('cart.index', ['cartItems' => Cart::items(), 'total' => Cart::total()]);
    }

    public function add($id, $request) {
        $quantita = isset($request['quantita']) ? max(1, (int)$request['quantita']) : 1;
        if($quantita < 1){
            FlashMessages::add('error', 'La quantità dev\'essere minimo di 1.');
            redirect('eventi/show/' . $id);
        }
        $repo = new EventoRepository();
        $evento = $repo->findById((int)$id);

        if (!$evento) {
            FlashMessages::add('error', 'Evento non trovato.');
            redirect('home');
        }

        Cart::add($evento, $quantita);
        FlashMessages::add('success', 'Evento aggiunto al carrello.');
        redirect('cart.index');
    }

    public function remove($id) {
        Cart::remove((int)$id);
        FlashMessages::add('success', 'Elemento rimosso dal carrello.');
        redirect('cart.index');
    }
}
