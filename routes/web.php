<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ReturnController;
use App\Http\Controllers\Web\WasteController;
use App\Http\Controllers\Web\InvoiceController;
use App\Http\Controllers\Web\PurchaseController;
use App\Http\Controllers\Web\InventoryCheckController;
use App\Http\Controllers\Web\ShoeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\TransactionController;
use App\Http\Controllers\Web\GiftSupplierController;
use App\Http\Controllers\Web\FactoryController;
use App\Http\Controllers\Web\RetailStoreController;
use App\Http\Controllers\Web\ChequeController;
use App\Http\Controllers\Web\BankAccountController;
use App\Http\Controllers\Web\GiftPurchaseController;
use App\Http\Controllers\Web\GiftController;
use App\Http\Controllers\Web\EmployeeController;
use App\Http\Controllers\Web\ExpenseController;
use App\Http\Controllers\Web\LoanController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\ColorController;
use App\Http\Controllers\Web\AccountBookController;
use App\Http\Controllers\Web\InventoryCheckEntryController;
use App\Http\Controllers\Web\RetailStoreExpenseController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::middleware('auth')->group(function () {
	Route::get('/', [HomeController::class, 'index'])->name('home');
	Route::get('home', function () {
		return redirect()->route('home');
	});
	Route::get('return/factory', [ReturnController::class, 'factoryPage'])->name('return.factory');
	Route::post('return/factory', [ReturnController::class, 'factory']);
	Route::get('return/retail-store', [ReturnController::class, 'retailStorePage'])->name('return.retail-store');
	Route::post('return/retail-store', [ReturnController::class, 'retailStore']);
	Route::get('return/pending', [ReturnController::class, 'pendingPage'])->name('return.pending');
	Route::post('return/pending/factory', [ReturnController::class, 'pendingFactory'])->name('return.pending.factory');
	Route::post('return/pending/retail-store', [ReturnController::class,'pendingRetailStore'])->name('return.pending.retail-store');
	Route::get('inventory-check/{inventoryCheck}/complete', [InventoryCheckController::class,'complete'])->name('inventory-check.complete');
	Route::post('inventory-check/{inventoryCheck}/resolve', [InventoryCheckController::class,'resolve'])->name('inventory-check.resolve');
	Route::get('factory/{factory}/closing', [FactoryController::class, 'closingPage'])->name('factory.closing-page');
	Route::post('factory/{factory}/closing', [FactoryController::class, 'closing'])->name('factory.closing');
	Route::get('account-book/{accountBook}/closing', [AccountBookController::class, 'closingPage'])->name('account-book.closing-page');
	Route::post('account-book/{accountBook}/closing', [AccountBookController::class, 'closing'])->name('account-book.closing');
	Route::get('account-book/{accountBook}/forward-balance', [AccountBookController::class,'forwardBalance'])->name('account-book.forward-balance');
	Route::get('barcode', [ShoeController::class, 'barcodePage'])->name('shoe.barcode-page');
	Route::post('barcode', [ShoeController::class, 'barcode'])->name('shoe.barcode');
	Route::get('purchase/{purchase}/barcode', [PurchaseController::class, 'barcode'])->name('purchase.barcode');
	Route::get('waste/shoes', [WasteController::class, 'shoePage'])->name('waste.shoes-page');
	Route::post('waste/shoes', [WasteController::class, 'shoe'])->name('waste.shoes');
	Route::get('waste/gifts', [WasteController::class, 'giftPage'])->name('waste.gifts-page');
	Route::post('waste/gifts', [WasteController::class, 'gift'])->name('waste.gifts');
	Route::resource('category', CategoryController::class)->except(['show']);
	Route::resource('color', ColorController::class)->except(['show']);
	Route::resource('factory', FactoryController::class);
	Route::resource('retail-store', RetailStoreController::class);
	Route::resource('account-book', AccountBookController::class)->only(['show']);
	Route::resource('shoe', ShoeController::class);
	Route::post('shoe/download', [ShoeController::class, 'download'])->name('show.download');
	Route::post('shoe/download/delete', [ShoeController::class, 'downloadDeleted'])->name('show.download.deleted');
	Route::resource('bank-account', BankAccountController::class);
	Route::resource('employee', EmployeeController::class);
	Route::resource('loan', LoanController::class);
	Route::resource('expense', ExpenseController::class);
	Route::resource('purchase', PurchaseController::class);
	Route::resource('invoice', InvoiceController::class)->except(['index']);
	Route::resource('gift', GiftController::class)->except(['show']);
	Route::resource('gift-supplier', GiftSupplierController::class);
	Route::get('/gift-suppliers', [GiftSupplierController::class, 'index'])->name('gift-supplier.index');
	Route::resource('gift-purchase', GiftPurchaseController::class)->except(['index']);
	Route::resource('transaction', TransactionController::class);

	Route::resource('cheque', ChequeController::class)->only(['index']);
	Route::resource('inventory-check', InventoryCheckController::class)->only(['index', 'create', 'store', 'show']);
	Route::resource('inventory-check-entry', InventoryCheckEntryController::class)->only(['store']);
    Route::resource('retail-store-expense', RetailStoreExpenseController::class)->only(['store']);
	Route::prefix('report')->group(function () {
		Route::get('transaction', [ReportController::class, 'transactionReportPage'])->name('report.transaction-page');
			Route::get('transaction/monthly', [ReportController::class,'monthlyTransactionReport'])->name('report.transaction.monthly');
			Route::get('transaction/yearly', [ReportController::class,'yearlyTransactionReport'])->name('report.transaction.yearly');
		Route::get('cash', [ReportController::class, 'cash'])->name('report.cash');
	});
	Route::prefix('ajax')->group(function () {
		Route::prefix('datalist')->group(function () {
			Route::get('factory', [FactoryController::class, 'datalist'])->name('datalist.factory');
			Route::get('retail-store', [RetailStoreController::class, 'datalist'])->name('datalist.retail-store');
			Route::get('retail-closing', [RetailStoreController::class, 'closingDatalist'])->name('datalist.retail-closing');
			Route::get('gift-supplier', [GiftSupplierController::class, 'datalist'])->name('datalist.gift-supplier');
			Route::get('employee', [EmployeeController::class,'datalist'])->name('datalist.employee');
			Route::get('loan', [LoanController::class,'datalist'])->name('datalist.loan');
			Route::get('expense', [ExpenseController::class,'datalist'])->name('datalist.expense');
			Route::get('category', [CategoryController::class, 'datalist'])->name('datalist.category');
			Route::get('color', [ColorController::class, 'datalist'])->name('datalist.color');
		});
		Route::prefix('tr')->group(function () {
			Route::get('purchase', [PurchaseController::class, 'tr'])->name('tr.purchase');
			Route::get('invoice', [InvoiceController::class, 'tr'])->name('tr.invoice');
			Route::get('gift', [InvoiceController::class, 'giftTr'])->name('tr.gift');
			Route::get('payment', [InvoiceController::class, 'paymentTr'])->name('tr.payment');
			Route::get('gift-purchase', [GiftPurchaseController::class, 'tr'])->name('tr.gift-purchase');
			Route::get('return/factory', [ReturnController::class, 'factoryTr'])->name('tr.return.factory');
			Route::get('return/retail-store', [ReturnController::class, 'retailStoreTr'])->name('tr.return.retail-store');
			Route::get('factory/closing/payment', [FactoryController::class, 'closingPaymentTr'])->name('tr.factory.closing.payment');
			Route::get('factory/closing/cheque', [FactoryController::class, 'closingChequeTr'])->name('tr.factory.closing.cheque');
			Route::get('retail-store/closing', [RetailStoreController::class, 'closingTr'])->name('tr.retail-store.closing');
			Route::get('barcode', [ShoeController::class, 'barcodeTr'])->name('tr.barcode');
		});
		Route::get('return/unlisted/{retailStore}', [ReturnController::class, 'unlistedReturns'])->name('ajax.return.unlisted');
		Route::get('shoe/show/{shoe}', [App\Http\Controllers\Main\ShoeController::class, 'show'])->name('ajax.shoe.show');
		Route::resource('cheque', 'Api\ChequeController', ['as' => 'ajax'])->only(['show']);
	});
	Route::get('images/{template}/{filename}', function ($template, $filename) { })->name('imagecache');
	Route::get('logout', [LoginController::class, 'logout'])->name('app.logout');
});