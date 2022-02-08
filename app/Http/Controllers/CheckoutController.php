<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Store;
use App\Payment\PagSeguro\CreditCard;
use App\Payment\PagSeguro\Boleto;
use Ramsey\Uuid\Uuid;




class CheckoutController extends Controller
{
    public function index() {
       try{
            //session()->forget('pagseguro_session_code');
        $categories = \App\Category::all(['name', 'slug']);
        if(!auth()->check()){
            return redirect ()->route('login');
        }

        if(!session()->has('cart')) return redirect()->route('home');
       
        $this->makePagSeguroSession();
        //var_dump(session()->get('pagseguro_session_code));
        
        $cartItems = array_map(function($line){
            return $line['amount'] * $line['price'];
        }, session()->get('cart'));

        $cartItems = array_sum($cartItems);

        //return view('checkout', compact('categories'));
        return view('checkout',  compact('cartItems'));
    } catch (\Exception $e){
        session()->forget('pagseguro_session_code');
        redirect()->route('checkout.index');
    }
       }

    public function proccess(Request $request){
        try{
            //dd($request->all());

            $dataPost = $request->all();
            $user = auth()->user();
            $cartItems = session()->get('cart');
            $stores = array_unique(array_column($cartItems, 'store_id'));
            $reference = Uuid::uuid4();

            $payment = $dataPost['paymentType'] === 'BOLETO'
                ? new Boleto($cartItems, $user, $reference, $dataPost['hash']) 
                : new CreditCard($cartItems, $user, $dataPost, $reference);

            $result = $payment->doPayment();

            $userOrder = [
                'reference' => $reference,
                'pagseguro_code' => $result->getCode(),
                'pagseguro_status' => $result->getStatus(),
                'items' => serialize($cartItems)                
            ];

            $userOrder = $user->orders()->create($userOrder);

            $userOrder->stores()->sync($stores);

            session()->forget('cart');
            session()->forget('pagseguro_session_code');

            $dataJson = [
                'status' => true,
                'message' => 'Pedido gerado com sucesso!',
                'order' => $reference
            ];

            if($dataPost['paymentType'] == 'BOLETO') $dataJson['link_boleto'] = $result->getPaymentLink();

            return response()->json([
                'data' => $dataJson
            ]);

        }catch (\Exception $e){
            $message = env('APP_DEBUG') ? simplexml_load_string($e->getMessage()) : 'Erro ao processar Pedido!';
            return response()->json([
                'data' => [
                    'status' => false,
                    'message' => $message
                ]
            ], 401);
        }
    }

    public function thanks()
    {
        return view('thanks');
    }

    public function notification(){
        try{
            $notification = new Notification();
            $notification = $notification->getTransaction();
            $reference = base64_decode($notification->getReference());

            //Atualizar Pedido
            $userOrder = UserOrder::whereReference($reference);
            $userOrder->update([
                'pagseguro_status' => $notification->getStatus()
            ]);

            if($notification->getStatus() == 3){
                //Liberação
                //Notificação de Pgto
                //Notificação de confirmação
            }
            
                return response()->json([], 204);
        } catch(\Exception $e){
            return response()->json([], 500);
    }}

    private function makePagSeguroSession(){
        if(!session()->has('pagseguro_session_code')){

            $sessionCode = \PagSeguro\Services\Session::create(
                \PagSeguro\Configuration\Configure::getAccountCredentials()
            );
            return session()->put('pagseguro_session_code', $sessionCode->getResult());
        }
        
    }
}