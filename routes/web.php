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
use App\Http\Controllers\Web\SettingController;
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
    Route::post('return/pending/retail-store', [ReturnController::class, 'pendingRetailStore'])->name('return.pending.retail-store');
    Route::get('inventory-check/{inventoryCheck}/complete', [InventoryCheckController::class, 'complete'])->name('inventory-check.complete');
    Route::post('inventory-check/{inventoryCheck}/resolve', [InventoryCheckController::class, 'resolve'])->name('inventory-check.resolve');
    Route::get('factory/{factory}/closing', [FactoryController::class, 'closingPage'])->name('factory.closing-page');
    Route::post('factory/{factory}/closing', [FactoryController::class, 'closing'])->name('factory.closing');
    Route::get('account-book/{accountBook}/closing', [AccountBookController::class, 'closingPage'])->name('account-book.closing-page');
    Route::post('account-book/{accountBook}/closing', [AccountBookController::class, 'closing'])->name('account-book.closing');
    Route::get('account-book/{accountBook}/forward-balance', [AccountBookController::class, 'forwardBalance'])->name('account-book.forward-balance');
    Route::get('barcode', [ShoeController::class, 'barcodePage'])->name('shoe.barcode-page');
    Route::post('barcode', [ShoeController::class, 'barcode'])->name('shoe.barcode');
    Route::get('purchase/{purchase}/barcode', [PurchaseController::class, 'barcode'])->name('purchase.barcode');
    Route::get('waste/shoes', [WasteController::class, 'shoePage'])->name('waste.shoes-page');
    Route::post('waste/shoes', [WasteController::class, 'shoe'])->name('waste.shoes');
    Route::get('waste/gifts', [WasteController::class, 'giftPage'])->name('waste.gifts-page');
    Route::post('waste/gifts', [WasteController::class, 'gift'])->name('waste.gifts');

    Route::resource('category', CategoryController::class)->except(['show']);
    Route::get('category/{category}/force-delete', [CategoryController::class, 'forceDelete'])->name('category.forceDelete');
    Route::get('category/{category}/restore', [CategoryController::class, 'restore'])->name('category.restore');

    Route::resource('color', ColorController::class)->except(['show']);
    Route::get('color/{color}/force-delete', [ColorController::class, 'forceDelete'])->name('color.forceDelete');
    Route::get('color/{color}/restore', [ColorController::class, 'restore'])->name('color.restore');

    Route::resource('factory', FactoryController::class);
    Route::get('factory/{factory}/force-delete', [FactoryController::class, 'forceDelete'])->name('factory.forceDelete');
    Route::get('factory/{factory}/restore', [FactoryController::class, 'restore'])->name('factory.restore');

    Route::resource('retail-store', RetailStoreController::class);
    Route::get('retail-store/{retail_store}/force-delete', [RetailStoreController::class, 'forceDelete'])->name('retail-store.forceDelete');
    Route::get('retail-store/{retail_store}/restore', [RetailStoreController::class, 'restore'])->name('retail-store.restore');

    Route::resource('account-book', AccountBookController::class)->only(['show']);
    Route::resource('shoe', ShoeController::class);
    Route::post('shoe/download', [ShoeController::class, 'download'])->name('show.download');
    Route::post('shoe/download/delete', [ShoeController::class, 'downloadDeleted'])->name('show.download.deleted');

    Route::resource('bank-account', BankAccountController::class);
    Route::get('bank-account/{bank_account}/force-delete', [BankAccountController::class, 'forceDelete'])->name('bank-account.forceDelete');
    Route::get('bank-account/{bank_account}/restore', [BankAccountController::class, 'restore'])->name('bank-account.restore');

    Route::resource('employee', EmployeeController::class);
    Route::get('employee/{employee}/force-delete', [EmployeeController::class, 'forceDelete'])->name('employee.forceDelete');
    Route::get('employee/{employee}/restore', [EmployeeController::class, 'restore'])->name('employee.restore');

    Route::resource('loan', LoanController::class);
    Route::get('loan/{loan}/force-delete', [LoanController::class, 'forceDelete'])->name('loan.forceDelete');
    Route::get('loan/{loan}/restore', [LoanController::class, 'restore'])->name('loan.restore');

    Route::resource('expense', ExpenseController::class);
    Route::get('expense/{expense}/force-delete', [ExpenseController::class, 'forceDelete'])->name('expense.forceDelete');
    Route::get('expense/{expense}/restore', [ExpenseController::class, 'restore'])->name('expense.restore');

    Route::resource('purchase', PurchaseController::class);
    Route::resource('invoice', InvoiceController::class)->except(['index']);

    Route::resource('gift', GiftController::class)->except(['show']);

    Route::get('gift/{gift}/force-delete', [GiftController::class, 'forceDelete'])->name('gift.forceDelete');
    Route::get('gift/{gift}/restore', [GiftController::class, 'restore'])->name('gift.restore');

    Route::resource('gift-supplier', GiftSupplierController::class);
    Route::get('gift-supplier/{gift_supplier}/force-delete', [GiftSupplierController::class, 'forceDelete'])->name('gift-supplier.forceDelete');
    Route::get('gift-supplier/{gift_supplier}/restore', [GiftSupplierController::class, 'restore'])->name('gift-supplier.restore');

    Route::get('/gift-suppliers', [GiftSupplierController::class, 'index'])->name('gift-supplier.index');
    Route::resource('gift-purchase', GiftPurchaseController::class)->except(['index']);
    Route::resource('transaction', TransactionController::class);

    Route::resource('cheque', ChequeController::class)->only(['index']);
    Route::resource('inventory-check', InventoryCheckController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('inventory-check-entry', InventoryCheckEntryController::class)->only(['store']);
    Route::resource('retail-store-expense', RetailStoreExpenseController::class)->only(['store']);

    Route::prefix('report')->group(function () {
        Route::get('transaction', [ReportController::class, 'transactionReportPage'])->name('report.transaction-page');
        Route::get('transaction/daily', [ReportController::class, 'dailyTransactionReport'])->name('report.transaction.daily');
        Route::get('transaction/monthly', [ReportController::class, 'monthlyTransactionReport'])->name('report.transaction.monthly');
        Route::get('transaction/yearly', [ReportController::class, 'yearlyTransactionReport'])->name('report.transaction.yearly');
        Route::get('cash', [ReportController::class, 'cash'])->name('report.cash');
    });

    // setting routes
    Route::prefix('setting')->group(function () {
        Route::get('/users', [SettingController::class, 'users'])->name('setting.users');
        Route::get('/permission', [SettingController::class, 'permission'])->name('setting.permission');
        // role permission
        Route::get('/role-permission/{id}', [SettingController::class, 'rolePermission'])->name('role.permission');
        Route::post('/permission/store', [SettingController::class, 'permissionStore'])->name('permission.store');
    });

    Route::prefix('ajax')->group(function () {
        Route::prefix('datalist')->group(function () {
            Route::get('factory', [FactoryController::class, 'datalist'])->name('datalist.factory');
            Route::get('retail-store', [RetailStoreController::class, 'datalist'])->name('datalist.retail-store');
            Route::post('retail-store/invoice/product/check', [RetailStoreController::class, 'invoiceProductCheck'])->name('invoice.product.check.retail-store');
            Route::get('retail-closing', [RetailStoreController::class, 'closingDatalist'])->name('datalist.retail-closing');
            Route::get('gift-supplier', [GiftSupplierController::class, 'datalist'])->name('datalist.gift-supplier');
            Route::get('employee', [EmployeeController::class, 'datalist'])->name('datalist.employee');
            Route::post('employee-limit', [EmployeeController::class, 'EmployPaymentLimit'])->name('datalist.employee.limit');
            Route::get('loan-receipt', [LoanController::class, 'loanReceipt'])->name('datalist.loan');
            Route::get('loan-payment', [LoanController::class, 'loanPayment'])->name('datalist.loan');
            Route::get('expense', [ExpenseController::class, 'datalist'])->name('datalist.expense');
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
        Route::get('cheque/{cheque}', [App\Http\Controllers\Main\ChequeController::class, 'show'])->name('ajax.cheque.show');

        Route::get('expense', [ExpenseController::class, 'datalist'])->name('datalist.expense');
        Route::get('category', [CategoryController::class, 'datalist'])->name('datalist.category');
        Route::get('color', [ColorController::class, 'datalist'])->name('datalist.color');
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
        // Route::resource('cheque', 'Main\ChequeController', ['as' => 'ajax'])->only(['show']);
        Route::get('cheque/{cheque}', [App\Http\Controllers\Main\ChequeController::class, 'show'])->name('ajax.cheque.show');
    });


    Route::get('images/{template}/{filename}', function ($template, $filename) {
    })->name('imagecache');

    Route::get('logout', [LoginController::class, 'logout'])->name('app.logout');
});
