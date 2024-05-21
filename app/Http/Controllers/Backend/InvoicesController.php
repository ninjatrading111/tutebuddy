<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    /**
     * admin Invoice page
     */
    public function index()
    {
        return view('backend.payment.teacher.invoices');
    }

    /**
     * Get Sales Invoices
     */
    public function salesInvoices()
    {
        // Do something
    }

    /**
     * Get Service Invoices
     */
    public function serviceInvoices()
    {
        
    }
}
