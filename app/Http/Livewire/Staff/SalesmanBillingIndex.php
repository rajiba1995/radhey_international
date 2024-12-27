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
    public $numberLength;

    public function submit(){
        $this->validate([
            'salesman_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $exists = SalesmanBilling::where('salesman_id', $value)->exists();
                    if ($exists) {
                        $fail('A billing range already exists for the selected salesman.');
                    }
                },
            ],
            'start_no' => [
                'required',
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
                        $salesmanName = $overlap->salesman ? $overlap->salesman->name : 'Unknown';
                        $fail("The start or end number overlaps with the existing range of salesman '{$salesmanName}' (Range: {$overlap->start_no} to {$overlap->end_no}).");
                    }
                },
            ],
            'end_no' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ((int)$value <= (int)$this->start_no) {
                        $fail('The end number must be greater than the start number.');
                    }
                },
            ],
        ]);

        $this->numberLength = strlen((string)$this->end_no);
        // Normalize the start and end numbers to match the length
        $normalizedStartNo = str_pad($this->start_no, $this->numberLength, '0', STR_PAD_LEFT);
        $normalizedEndNo = str_pad($this->end_no, $this->numberLength, '0', STR_PAD_LEFT);

        $totalCount = (int)$this->end_no - (int)$this->start_no + 1;
        // Calculate no_of_used
        $usedCount = SalesmanBilling::whereBetween('start_no', [$this->start_no, $this->end_no])
                                        ->orWhereBetween('end_no', [$this->start_no, $this->end_no])
                                        ->where('no_of_used', '>', 0)
                                        ->count();

        SalesmanBilling::create([
            'salesman_id' => $this->salesman_id,
            'start_no' => $normalizedStartNo,
            'end_no' => $normalizedEndNo,
            'total_count'=> $totalCount,
            'no_of_used' => $usedCount,
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
        'salesman_id' => [
            'required',
            'exists:users,id', // Ensure the salesman exists in the users table
            function ($attribute, $value, $fail) {
                // Check if the selected salesman already has an active billing range (excluding current record)
                $exists = SalesmanBilling::where('salesman_id', $value)
                                         ->where('id', '!=', $this->billing_id) // Exclude the current record
                                         ->exists();
                if ($exists) {
                    $fail('A billing range already exists for the selected salesman.');
                }
            },
        ],
        'start_no' => [
            'required',
            function ($attribute, $value, $fail) {
                $overlap = SalesmanBilling::where(function ($query) {
                    // Check for overlap of start_no and end_no
                    $query->whereBetween('start_no', [$this->start_no, $this->end_no])
                          ->orWhereBetween('end_no', [$this->start_no, $this->end_no])
                          ->orWhere(function ($query) {
                              $query->where('start_no', '<=', $this->start_no)
                                    ->where('end_no', '>=', $this->end_no);
                          });
                })
                ->where('id', '!=', $this->billing_id) // Exclude the current record
                ->where('salesman_id', $this->salesman_id)
                ->first();

                if ($overlap) {
                    $salesmanName = $overlap->salesman ? $overlap->salesman->name : 'Unknown';
                    $fail("The start or end number overlaps with the existing range of salesman '{$salesmanName}' (Range: {$overlap->start_no} to {$overlap->end_no}).");
                }
            },
        ],
        'end_no' => [
            'required',
            function ($attribute, $value, $fail) {
                if ((int)$value <= (int)$this->start_no) {
                    $fail('The end number must be greater than the start number.');
                }
            },
        ],
    ]);
        $this->numberLength = strlen((string)$this->end_no);
        // Normalize the start and end numbers to match the length
        $normalizedStartNo = str_pad($this->start_no, $this->numberLength, '0', STR_PAD_LEFT);
        $normalizedEndNo = str_pad($this->end_no, $this->numberLength, '0', STR_PAD_LEFT);

        // Calculate the total_count
         $totalCount = (int)$this->end_no - (int)$this->start_no + 1;
        // Calculate no_of_used
       
        $usedCount = SalesmanBilling::whereBetween('start_no', [$this->start_no, $this->end_no])
                                        ->orWhereBetween('end_no', [$this->start_no, $this->end_no])
                                        ->where('no_of_used', '>', 0) 
                                        ->count();
        
        $billing = SalesmanBilling::findOrFail($this->billing_id);
        $billing->update([
            'salesman_id' => $this->salesman_id,
            'start_no' => $normalizedStartNo,
            'end_no' => $normalizedEndNo,
            'total_count' => $totalCount,
            'no_of_used' => $usedCount,
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
