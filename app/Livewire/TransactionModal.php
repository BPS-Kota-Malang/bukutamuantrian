<?php

namespace App\Livewire;

use Livewire\Component;

class TransactionModal extends Component
{
    public $transaction;
    public $customer;
    public $queue;

    protected $listeners = ['showTransactionModal'];

    public $showModal = false; // To control modal visibility

    public function render()
    {
        return view('livewire.transaction-modal');
    }

    public function showTransactionModal($data)
    {
        $this->transaction = $data['transaction'];
        $this->customer = $data['customer'];
        $this->queue = $data['queue'];
        $this->dispatchBrowserEvent('openModal');
    }

    public function downloadPdf()
    {
        // Logic to generate and download the PDF
        return response()->stream(function () {
            // Use a PDF generation library to create the PDF
            // For example, using DomPDF:
            $pdf = \PDF::loadView('pdf.transaction', [
                'transaction' => $this->transaction,
                'customer' => $this->customer,
                'queue' => $this->queue,
            ]);
            return $pdf->stream('transaction.pdf');
        });
    }
}
