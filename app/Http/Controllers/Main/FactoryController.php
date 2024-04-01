<?php

namespace App\Http\Controllers\Main;

use App\Models\Factory;
use App\Models\AccountBook;
use App\Models\Transaction;
use App\Models\Cheque;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FactoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Factory::orderBy('id','desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Responsew
     */
    public function store(Request $request)
    {
        $factory = new Factory;
        $factory->fill($request->all());
        $factory->save();

        if($factory){
            $account_book = new AccountBook;
            $account_book->account_id =$factory->id;
            $account_book->account_type ='factory';
            $account_book->save();
        }
        
        return $factory;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Factory  $factory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    
        $factory = Factory::with('accountBooks.transactionsTo',)->find($id);
        return $factory;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Factory  $factory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Factory $factory)
    {
        $factory->fill($request->all());
        $factory->save();

        return $factory;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Factory  $factory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Factory $factory)
    {
        $factory->delete();
        return collect(['success' => 'কারখানাদারের তথ্য মুছে ফেলা হয়েছে।']);
    }

    public function closing(Request $request, Factory $factory) {
        $accountBook = $factory->getCurrentAccountBook();
        $balance = $accountBook->balance;

        $accountBook->fill($request->all());

        $payments = $request->input('payment');
        $payment_sum = 0;
        foreach($payments as $payment) {
            if(empty($payment['amount'])) {
                continue;
            }
            $transaction = Transaction::createTransaction('factory', $factory->id, 'expense', $payment['method'], $payment['amount'], '', null, $accountBook->id);
            $payment_sum += $payment['amount'];
        }

        $cheques = $request->input('cheque');
        $cheque_sum = 0;
        foreach($cheques as $cheque_entry) {
            if(empty($cheque_entry['id'])) {
                continue;
            }
            $cheque = Cheque::issue($cheque_entry['id'], $accountBook, $cheque_entry['amount'], $cheque_entry['due_date'], null, $accountBook->id);
            $cheque_sum += $cheque_entry['amount'];
        }

        $accountBook->closing_balance = $balance - $accountBook->commission - $accountBook->staff - $payment_sum - $cheque_sum;
        $accountBook->open = false;
        $accountBook->save();
        $factory->accountBooks()->save(new AccountBook());
        
        $factory->append('current_book');
        return $factory;
    }
}
