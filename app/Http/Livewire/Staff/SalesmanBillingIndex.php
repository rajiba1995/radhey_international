<?php

namespace App\Http\Livewire\Staff;

use Livewire\Component;
use App\Models\User;
use App\Models\SalesmanBilling;

class SalesmanBillingIndex extends Component
{
    public $salesman_id;
    public $start_no;
    public $end_no;
    public $billing_id;

    public function submit(){
        $this->validate([
            'salesman_id' => 'required|exists:users,id',
            'start_no' => [
                'required',
                'digits:4', // Ensure it's 4 digits
                function ($attribute, $value, $fail) {
                    $overlap = SalesmanBilling::where(function ($query) {
                        $query->whereBetween('start_no', [$this->start_no, $this->end_no])
                              ->orWhereBetween('end_no', [$this->start_no, $this->end_no])
                              ->orWhere(function ($query) {
                                  $query->where('start_no', '<=', $this->start_no)
                                        ->where('end_no', '>=', $this->end_no);
                              });
                    })->first();                    
    
                    if ($overlap) {
                        $salesmanName = $overlap->salesman ? $overlap->salesman->name : 'Unknown'; // Retrieve salesman's name
                        $fail("The start or end number overlaps with the existing range of salesman '{$salesmanName}' (Range: {$overlap->start_no} to {$overlap->end_no}).");
                    }
                },
            ],
            'end_no' => [
                'required',
                'digits:4', // Ensure it's 4 digits
                function ($attribute, $value, $fail) {
                    if ((int)$value <= (int)$this->start_no) {
                        $fail('The end number must be greater than the start number.');
                    }
                },
            ],
            'end_no' => function ($attribute, $value, $fail) {
                if ((int)$value <= (int)$this->start_no) {
                    $fail('The end number must be greater than the start number.');
                }
            },
        ]);

        SalesmanBilling::create([
            'salesman_id' => $this->salesman_id,
            'start_no' => $this->start_no,
            'end_no' => $this->end_no,
        ]);

        $this->reset(['salesman_id', 'start_no', 'end_no']);
        session()->flash('message', 'Salesman billing number added successfully!');
    }
    public function edit($id){
        $billing = SalesmanBilling::findOrFail($id);
        $this->billing_id = $billing->id;
        $this->salesman_id = $billing->salesman_id;
        $this->start_no = $billing->start_no;
        $this->end_no = $billing->end_no;
    }

    public function update()
    {
        $this->validate([
            'salesman_id' => 'required|exists:users,id',
            'start_no' => [
                'required',
                'digits:4',
                function ($attribute, $value, $fail) {
                    $overlap = SalesmanBilling::where(function ($query) {
                        $query->whereBetween('start_no', [$this->start_no, $this->end_no])
                            ->orWhereBetween('end_no', [$this->start_no, $this->end_no])
                            ->orWhere(function ($query) {
                                $query->where('start_no', '<=', $this->start_no)
                                        ->where('end_no', '>=', $this->end_no);
                            });
                    })
                    ->where('id', '!=', $this->billing_id) // Exclude the current record
                    ->first();                    

                    if ($overlap) {
                        $salesmanName = $overlap->salesman ? $overlap->salesman->name : 'Unknown'; // Retrieve salesman's name
                        $fail("The start or end number overlaps with the existing range of salesman '{$salesmanName}' (Range: {$overlap->start_no} to {$overlap->end_no}).");
                    }
                },
            ],
            'end_no' => [
                'required',
                'digits:4',
                function ($attribute, $value, $fail) {
                    if ((int)$value <= (int)$this->start_no) {
                        $fail('The end number must be greater than the start number.');
                    }
                },
            ],
        ]);

        $billing = SalesmanBilling::findOrFail($this->billing_id);
        $billing->update([
            'salesman_id' => $this->salesman_id,
            'start_no' => $this->start_no,
            'end_no' => $this->end_no,
        ]);

        $this->reset(['salesman_id', 'start_no', 'end_no']);
        session()->flash('message', 'Salesman billing number updated successfully!');
    }

    public function destroy($id)
    {
        SalesmanBilling::findOrFail($id)->delete();
        session()->flash('message', 'Salesman billing number deleted successfully!');
    }


    public function render()
    {
        $salesman = User::where('designation',2)->get();
        $billings = SalesmanBilling::with('salesman')->get();
        return view('livewire.staff.salesman-billing-index',['salesmans'=>$salesman, 'billings' => $billings,]);
    }
}
