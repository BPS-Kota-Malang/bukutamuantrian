<?php

namespace App\Filament\Guest\Pages;

use App\Models\Customer;
use App\Models\Education;
use App\Models\Institution;
use App\Models\Purpose;
use App\Models\Queue;
use App\Models\Service;
use App\Models\SubMethod;
use App\Models\Transaction;
use App\Models\University;
use App\Models\Work;
use App\Services\QueueService;
use App\Services\WhatsappService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;



class PublicTransaction extends Page implements HasForms
{
    // use CreateRecord\Concerns\HasWizard;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.guest.pages.public-transaction';

    public $name;
    public $phone;
    public $email;
    public $age;
    public $gender;
    public $work_id;
    public $service_id;
    public $education_id;
    public $university_id;
    public $institution_id;
    public $sub_method_id;
    public $purpose_id;

    public $customer;
    public $transaction;
    public $queue;

    public $services;
    public $selectedService;

    protected $queueService;
    public $openModal = false;

    // protected bool $showTransactionModal = false;

    // public function __construct(QueueService $queueService)
    // {
    //     $this->queueService = $queueService; // Inject the service in the constructor
    // }


    public function mount()
    {
        // Ensure the form fields are initialized
        $this->form->fill([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'age' => $this->age,
            'gender' => $this->gender,
            'work_id' => $this->work_id,
            'service_id' => $this->service_id,
            'education_id' => $this->education_id,
            'university_id' => $this->university_id ?? null,
            'institution_id' => $this->institution_id ?? null,
            'sub_method_id' => $this->sub_method_id,
            'service_id' => $this->service_id,
            'purpose_id' => $this->purpose_id,
        ]);
        $this->selectedService = null;

        $this->services = Service::all()->toArray();
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Data Pribadi')
                        ->schema([
                            TextInput::make('email')
                                ->required()
                                ->email()
                                ->afterStateUpdated(function (callable $set, $state) {
                                    $this->autofillCustomerData($state);
                                })
                                ->reactive() // Ensure the field is reactive
                                ->extraAttributes([
                                    'onkeypress' => 'if(event.key === "Tab") { this.dispatchEvent(new Event("change")); }',
                                ]),
                            TextInput::make('name')
                                ->label('Masukkan Nama Anda')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->required()
                                ->label('Masukkan Nomor Handphone')
                                ->tel()
                                ->telRegex('/^(?:\+62|62|0)8[1-9][0-9]{6,10}$/'),
                            TextInput::make('age')
                                ->label('Masukkan Usia Anda')
                                ->numeric()
                                ->maxLength(2)              // Maximum length of 2 characters
                                ->required(),
                            Select::make('gender')
                                ->label('Pilih Jenis Kelamin')
                                ->options([
                                    'male' => 'Laki-Laki',
                                    'female' => 'Perempuan',
                                ])
                                ->required(),
                        ]),
                    Wizard\Step::make('Pendidikan & Pekerjaan')
                        ->schema([
                            Select::make('education_id')
                                ->label('Pendidikan terakhir')
                                ->options(Education::all()->pluck('name', 'id'))
                                ->required(),
                            Select::make('work_id')
                                ->label('Pilih Pekerjaan Anda')
                                ->options(Work::all()->pluck('name', 'id'))
                                ->required()
                                ->reactive() // Make this field reactive to allow dynamic form updates
                                ->afterStateUpdated(function (callable $set, $state) {
                                    $set('university_id', null); // Reset 'university_id' when 'work_id' is updated
                                    $set('institution_id', null); // Reset 'institution_id' when 'work_id' is updated
                                }),
                            Select::make('university_id')
                                ->label('Pilih Universitas')
                                ->options(University::all()->pluck('name', 'id')) // Make sure to populate this with your actual university data
                                ->required(fn(Get $get) => $get('work_id') === '1')
                                ->searchable()
                                ->hidden(fn(Get $get) => $get('work_id') !== '1') // Show only when work_id is 1
                                ->reactive()
                                ->live()
                                ->createOptionForm([ // Allow adding a new institution if not found
                                    TextInput::make('name')
                                        ->label('Masukkan Nama Universitas')
                                        ->required(),
                                ])
                                ->createOptionUsing(function ($data) {
                                    return University::create([
                                        'name' => $data['name'],
                                    ])->id;
                                }),
                            Select::make('institution_id')
                                ->label('Pilih Institusi')
                                ->options(Institution::all()->pluck('name', 'id')) // Populated with current institutions
                                // ->relationship('institution', 'name')
                                ->required(fn(Get $get) => $get('work_id') && $get('work_id') !== '1') // Required only if work_id is NOT '1'
                                ->hidden(fn(Get $get) => !$get('work_id') || $get('work_id') == '1') // Show only when work_id is not '1'
                                ->reactive()
                                ->createOptionForm([ // Allow adding a new institution if not found
                                    TextInput::make('name')
                                        ->label('Masukkan Nama Institusi')
                                        ->required(),
                                ])
                                ->createOptionUsing(function ($data) {
                                    return Institution::create([
                                        'name' => $data['name'],
                                    ])->id;
                                })
                                ->searchable(), // Allows searching through the institution list
                        ]),
                    Wizard\Step::make('Layanan')
                        ->schema([
                            Select::make('sub_method_id')
                                ->label('Pilih Media Layanan')
                                ->options(SubMethod::all()->pluck('name', 'id'))
                                ->required(),
                            Select::make('purpose_id')
                                ->label('Tujuan Penggunaan Layanan')
                                ->options(Purpose::all()->pluck('name', 'id'))
                                ->reactive()
                                ->required(),
                            Select::make('service_id')
                                ->label('Pilih Layananan yang dibutuhkan')
                                ->options(Service::all()->pluck('name', 'id'))
                                ->required(),
                        ]),
                ])->submitAction(new HtmlString('<button class="bg-yellow-200" type="submit">Submit</button>')),
            ]);
    }

    protected function autofillCustomerData(string $email): void
    {
        // Check if a customer exists with the provided email
        $customer = Customer::where('email', $email)->first();

        if ($customer) {
            // Autofill the form with the customer's data
            $this->form->fill([
                'name' => $customer->name,
                'phone' => $customer->phone,
                'age' => $customer->age,
                'gender' => $customer->gender,
                'work_id' => $customer->work_id,
                'education_id' => $customer->education_id,
                'university_id' => $customer->university_id,
                'institution_id' => $customer->institution_id,
            ]);
        }
    }

    public function submit()
    {
        // Start a database transaction to ensure all operations succeed or none
        DB::beginTransaction();

        try {
            // Save customer data inside a try-catch block
            try {
                $normalizedPhone = Customer::normalizePhoneNumber($this->form->getState()['phone']);

                $this->customer = Customer::firstOrCreate(
                    [
                        'email' => $this->form->getState()['email'],
                        'phone' => $normalizedPhone
                    ],
                    [
                        'name' => $this->form->getState()['name'],
                        'age' => $this->form->getState()['age'],
                        'gender' => $this->form->getState()['gender'],
                        'work_id' => $this->form->getState()['work_id'],
                        'education_id' => $this->form->getState()['education_id'],
                        'university_id' => $this->form->getState()['university_id'] ?? null,
                        'institution_id' => $this->form->getState()['institution_id'] ?? null,
                    ]
                );
            } catch (\Exception $e) {
                // Rollback the transaction
                DB::rollBack();
                // Log error and return user-friendly message
                Log::error('Error saving customer: ' . $e->getMessage());
                return $this->notifyError('An error occurred while saving customer data.');
            }

            // Save transaction data
            try {
                // Resolve the QueueService from the container
                $queueService = app(QueueService::class);
                $whatsappService = app(WhatsappService::class);

                $this->transaction = Transaction::create([
                    'customer_id' => $this->customer->id,
                    'service_id' => $this->form->getState()['service_id'],
                    'purpose_id' => $this->form->getState()['purpose_id'],
                    'sub_method_id' => $this->form->getState()['sub_method_id'],
                ]);

                // Handle queue creation for specific services
                $layanan = $this->form->getState()['sub_method_id'];

                $service_id = $this->transaction->service_id;
                // dd($layanan);
                if ($layanan == 4) {
                    try {
                        // Get the last queue number or default to 1
                        $queueNumber = $queueService->getLastQueue() ?? 1;

                        // Validate that the queue number is not null
                        if (empty($queueNumber)) {
                            throw new \Exception('Queue number generation failed.');
                        }

                        $this->queue = Queue::create([
                            'date' => Carbon::today(),
                            'number' => $queueNumber,
                            'transaction_id' => $this->transaction->id,
                        ]);
                    } catch (\Exception $e) {
                        // Rollback the transaction
                        DB::rollBack();
                        // Log error and return user-friendly message
                        Log::error('Error creating queue: ' . $e->getMessage());

                        // Notify the user with an error message
                        Notification::make()
                            ->danger()
                            ->title('Error')
                            ->body('Error creating queue: ' . $e->getMessage())
                            ->send();

                        return;
                    }
                }
            } catch (\Exception $e) {
                // Rollback the transaction
                DB::rollBack();

                // Log error and return user-friendly message
                Log::error('Error saving transaction: ' . $e->getMessage());

                // Notify the user with an error message
                Notification::make()
                    ->danger()
                    ->title('Error')
                    ->body('An error occurred while saving transaction data :' . $e->getMessage())
                    ->send();

                return;
            }

            // Commit the transaction since everything is successful
            DB::commit();



            // Assuming this is the completion part of the transaction process in your Filament resource
            // $this->emit('showTransactionModal', $this->transaction, $this->customer, $this->queue);
            $layanan_choosed = SubMethod::find($layanan)->value('name');

            if ($layanan == 4) {

                $prefix = Service::find($service_id)->code ?? ''; // Use the code column for prefix, default to empty string if null


                try {
                    $queueDate = now()->format('d M Y');
                    $customerName = $this->customer->name;
                    $serviceName = Service::find($this->transaction->service_id)->name;
                    $queueNumberFormatted = str_pad($queueNumber, 3, '0', STR_PAD_LEFT);
                    $whatsappService->sendMessage([
                        'phone' => $this->customer->phone, // Send to the customer's phone
                        'message' => "Halo, Sahabat Data!\n\n" .
                            "Terima kasih telah menggunakan layanan kami, berikut adalah detail antrian Anda:\n" .
                            "Nama: {$customerName}\n" .
                            "Nomor Antrian: {$prefix}-{$queueNumberFormatted}\n" .
                            "Layanan yang Dibutuhkan: {$serviceName}\n" .
                            "Media Layanan yang digunakan: {$layanan_choosed}\n" .
                            "Tanggal pelayanan: {$queueDate}\n\n" .
                            "Tunjukkan pesan ini kepada petugas pelayanan saat anda datang ke PST BPS Kota Malang.",
                    ]);
                } catch (\Exception $e) {

                    // Log error and return user-friendly message
                    Log::error('Error Send Whatsapp Message But Data Already Saved :' . $e->getMessage());

                    // Notify the user with an error message
                    Notification::make()
                        ->danger()
                        ->title('Error')
                        ->body('Error Send Whatsapp Message But Data Already Saved : ' . $e->getMessage())
                        ->send();

                    return;
                }
            } else {
                try {

                    $queueDate = now()->format('d M Y');
                    $customerName = $this->customer->name;
                    $serviceName = Service::find($this->transaction->service_id)->name;

                    $whatsappService->sendMessage([
                        'phone' => $this->customer->phone, // Send to the customer's phone
                        'message' => "Halo, Sahabat Data!\n\n" .
                            "Terima kasih telah menggunakan layanan kami, berikut adalah detail transaksi Anda:\n" .
                            "Nama: {$customerName}\n" .
                            "Layanan yang Dibutuhkan: {$serviceName}\n" .
                            "Media Layanan yang digunakan: {$layanan_choosed}\n" .
                            "Tanggal pelayanan: {$queueDate}\n\n" .
                            "Terima kasih telah menggunakan layanan kami!",
                    ]);
                } catch (\Exception $e) {
                    // Rollback the transaction
                    DB::rollBack();
                    // Log error and return user-friendly message
                    Log::error('Error Send Whatsapp Message But Data Already Saved :' . $e->getMessage());

                    // Notify the user with an error message
                    Notification::make()
                        ->danger()
                        ->title('Error')
                        ->body('Error Send Whatsapp Message But Data Already Saved : ' . $e->getMessage())
                        ->send();

                    return;
                }
            }

            Notification::make()
                ->success()
                ->title('Success')
                ->body('Data saved successfully')
                ->send();

            try {
                // Assuming this is inside your submit method
                $this->dispatchBrowserEvent('showTransactionModal', [
                    'transaction' => $this->transaction,
                    'customer' => $this->customer,
                    'queue' => $this->queue,
                ]);
                $this->showTransactionModal();
                // $this->openModal = true;
                // $this->emit('showTransactionModal', [
                //     'transaction' => $this->transaction,
                //     'customer' => $this->customer,
                //     'queue' => $this->queue,
                // ]);

            } catch (\Exception $e) {
                Log::error('Error show Modal: ' . $e->getMessage());

                // Notify the user with an error message
                Notification::make()
                    ->danger()
                    ->title('Error')
                    ->body('An error occurred while show modal :' . $e->getMessage())
                    ->send();
            }
            // Redirect to the desired route with success message
            return redirect()->route('filament.guest.pages.public-transaction')
                ->with('success', 'Data saved successfully.');
        } catch (\Exception $e) {
            // Rollback in case of any unforeseen errors
            DB::rollBack();

            // Log the error
            Log::error('Unexpected error: ' . $e->getMessage());

            // Display a notification to the user
            Notification::make()
                ->danger()
                ->title('Unexpected Error')
                ->body('An unexpected error occurred. Please try again.')
                ->send();

            return;
        }
    }

    // protected function showTransactionModal()
    // {
    //     $this->openModal = true;// Open the modal with transaction details
    // }

    protected function transactionAction(): Action
    {
        return Action::make('transactionModal')
            ->label('Transaction Details')
            ->modalButton('Close')
            ->modalHeading('Transaction Completed')
            ->modalContent(view('filament.guest.pages.transaction-modal', [
                'transaction' => $this->transaction,
                'customer' => $this->customer,
                'queue' => $this->queue,
            ]));
    }

    public function actions(): array
    {
        return [
            Action::make('showTransactionDetails') // Name of the action
                ->label('View Transaction Details')  // The button text that will be shown
                ->modalHeading('Transaction Details') // The modal's heading
                ->modalButton('Close') // The button text to close the modal
                ->modalWidth('lg') // Optional: you can define the size of the modal (lg, sm, etc.)
                ->action(function () {
                    // When clicked, it will trigger showing the modal
                    $this->showTransactionModal();
                })
                ->modalContent(view('filament.guest.pages.transaction-modal', [
                    'transaction' => $this->transaction,  // Pass the transaction data to the modal view
                    'customer' => $this->customer,
                    'queue' => $this->queue,
                ])),
        ];
    }

    // Modal that will be conditionally displayed
    public function getModal(): array
    {
        return [
            'modal' => [
                'title' => 'Transaction Completed',
                'content' => view('filament.guest.pages.transaction-modal', [
                    'transaction' => $this->transaction,
                    'customer' => $this->customer,
                    'queue' => $this->queue,
                ]),
                'open' => $this->openModal, // This is a boolean flag that controls the modal visibility
            ],
        ];
    }
}
