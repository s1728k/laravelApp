<?php

namespace App\Traits;

use App\App;
use App\RechargeHistory;
use Illuminate\Http\Request;

use PaytmWallet;

trait Paytm
{

    public function recharge(Request $request)
    {
    	\Log::Info($this->fc.'recharge');
        $amount = $this->rechargePlans()[$request->plan]['amount'];
        $id = RechargeHistory::create([
            'user_id' => \Auth::user()->id,
            'plan' => $this->rechargePlans()[$request->plan]['plan'],
            'status' => 'processing',
            'expiry_date' => Date('Y-m-d', strtotime("+".$this->rechargePlans()[$request->plan]['validity']." days")),
            'recharge_date' => date("Y-m-d"),
            'recharge_amount' => $amount,
            'tax' => 0,
            'top_up' => $amount,
        ])->id;
        $payment = PaytmWallet::with('receive');
        $payment->prepare([
          'order' => $id,
          'user' => \Auth::user()->id,
          'mobile_number' => '54646546',
          'email' => \Auth::user()->email,
          'amount' => $amount,
          'callback_url' => env('APP_URL').'/user/payment/status'
        ]);
        return $payment->view($this->theme.'.user.order')->receive();
    }

    public function paymentCallback()
    {
    	\Log::Info($this->fc.'paymentCallback');
        $transaction = PaytmWallet::with('receive');
        
        $response = $transaction->response();
        
        if($transaction->isSuccessful()){
            $rh = RechargeHistory::findOrFail($response['ORDERID']);
            $plan = strtok($rh->plan, ' ');
            $rh->update([
                'status' => 'paid',
                'expiry_date' => Date('Y-m-d', strtotime("+".$this->rechargePlans()[$plan]['validity']." days")),
                'recharge_date' => date("Y-m-d"),
            ]);
            $rh->save();
            \Auth::user()->recharge_balance=\Auth::user()->recharge_balance + $this->rechargePlans()[$plan]['amount'];
            \Auth::user()->recharge_expiry_date=$rh->expiry_date;
            \Auth::user()->save();
        }else if($transaction->isFailed()){
        	RechargeHistory::findOrFail($response['ORDERID'])->update(['status' => 'not paid']);
        }else if($transaction->isOpen()){
        	RechargeHistory::findOrFail($response['ORDERID'])->update(['status' => 'open/processing']);
        }
        return view($this->theme.'.user.payment_status')->with([
        	'res' => $response,
        ]);
    }

    public function statusCheck($id){
    	\Log::Info($this->fc.'statusCheck');
        $status = PaytmWallet::with('status');
        $status->prepare(['order' => $id]);
        $status->check();
        
        $response = $status->response();
        
        if($status->isSuccessful()){
        }else if($status->isFailed()){
        }else if($status->isOpen()){
        }
        return view($this->theme.'.user.payment_status')->with([
        	'res' => $response,
        ]);
    }

    public function refund($id){
        \Log::Info($this->fc.'refund');
        $order = RechargeHistory::findOrFail($id);
        $refund = PaytmWallet::with('refund');
        $refund->prepare([
            'order' => $order->id,
            'reference' => uniqid($order->id),
            'amount' => $order->recharge_amount,
            'transaction' => $this->getTransactionId($order->id)
        ]);
        $refund->initiate();
        $response = $refund->response();
        \Log::Info($response);
        
        // if($refund->isSuccessful()){
        // }else if($refund->isFailed()){
        // }else if($refund->isOpen()){
        // }else if($refund->isPending()){
        // }
        return view($this->theme.'.user.payment_status')->with([
            'res' => $response,
        ]);
    }

    public function refundStatus($id){
        \Log::Info($this->fc.'refundStatus');
        $refundStatus = PaytmWallet::with('refund_status');
        $refundStatus->prepare([
            'order' => $id,
            'reference' => "refund-order-10",
        ]);
        $refundStatus->check();
        
        $response = $refundStatus->response();
        
        if($refundStatus->isSuccessful()){
        }else if($refundStatus->isFailed()){
        }else if($refundStatus->isOpen()){
        }else if($refundStatus->isPending()){
        }
        return view($this->theme.'.user.payment_status')->with([
            'res' => $response,
        ]);
    }

    private function getTransactionId($order_id){
        \Log::Info($this->fc.'getTransactionId');
        $status = PaytmWallet::with('status');
        $status->prepare(['order' => $order_id]);
        $status->check();
        
        $response = $status->response();
        return $response['TXNID'];
        
        if($status->isSuccessful()){
        }else if($status->isFailed()){
        }else if($status->isOpen()){
        }
        return $response['TXNID']??null;
    }

}