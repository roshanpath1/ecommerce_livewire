<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Mail\OrderPlaced;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session;
use Livewire\Attributes\Title;
use Livewire\Component;
use Stripe\Stripe;

#[Title('Checkout')]
class CheckOutPage extends Component
{
    public $phone;
    public $city;
    public $country;

    public $first_name;
    public $last_name;
    public $street_address;
    public $payment_method;
    public $shipping = 100;
    public $taxes = 50;
    public function mount()
    {
        $cart_items = CartManagement::getCartItemsFromCookie();
        if (count($cart_items) == 0) {
            return redirect('/products');
        }
    }


    public function placeOrder()
    {
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|max:10',
            'street_address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'payment_method' => 'required',
        ]);

        $cart_items = CartManagement::getCartItemsFromCookie();
        $line_items = [];
        foreach ($cart_items as $item) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'NPR',
                    'unit_amount' => $item['unit_amount'] * 100,
                    'product_data' => [
                        'name' => $item['name'],
                    ]

                ],
                'quantity' => $item['quantity'],
            ];
        }
        $order = new Order();
        $order->user_id = Auth::id();
        $order->grand_total = CartManagement::calculateGrandTotal($cart_items);
        $order->payment_method = $this->payment_method;
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->currency = 'NPR';
        $order->shipping_method = null;
        $order->shipping_amount = 100;
        $order->notes = 'Order placed by' . Auth::user()->name;

        $address = new Address();
        $address->first_name = $this->first_name;
        $address->last_name = $this->last_name;
        $address->phone = $this->phone;
        $address->street_address = $this->street_address;
        $address->city = $this->city;
        $address->country = $this->country;

        $redirect_url = '';

        if ($this->payment_method == 'stripe') {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $sessionCheckout = Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => Auth::user()->email,
                'line_items' => $line_items,
                'mode' => 'payment',
                'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cancel'),
            ]);
            $redirect_url = $sessionCheckout->url;
        }
         else {
            $redirect_url = route('success');
        }
        $order->save();
        $address->order_id = $order->id;
        $address->save();
        $order->items()->createMany($cart_items);
        CartManagement::clearCartItems();
        // Mail::to(request()->user())->send(new OrderPlaced($order));
        return redirect($redirect_url);
        // ->route('success');
    }

    public function render()
    {
        $cart_items = CartManagement::getCartItemsFromCookie();
        $grand_total = CartManagement::calculateGrandTotal($cart_items);

        return view('livewire.check-out-page', [
            'cart_items' => $cart_items,
            'grand_total' => $grand_total,
        ]);
    }
}
