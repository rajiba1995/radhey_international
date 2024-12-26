<?php

namespace App\Http\Livewire\Staff;

use Livewire\Component;
use App\Models\City;

class StaffCities extends Component
{
    public $cities;  
    public $selectedCity;  
    public $submittedCities = [];

    protected $rules = [
        'selectedCity' => 'required|exists:cities,id',  
    ];

    public function mount()
    {
        $this->cities = City::all(); 

        if (session()->has('submittedCities')) {
            $this->submittedCities = session('submittedCities');
        }
    }

    public function submit(){

         $this->validate();

         if ($this->selectedCity) {
            $cityName = City::find($this->selectedCity)->name;

            if (!in_array($cityName, $this->submittedCities)) {
                $this->submittedCities[] = $cityName;
            }

            session()->put('submittedCities', $this->submittedCities);
        }

        $this->selectedCity = null;
    }

    public function deleteCity($cityName)
    {
        $this->submittedCities = array_filter($this->submittedCities, function($city) use ($cityName) {
            return $city !== $cityName;
        });

        $this->submittedCities = array_values($this->submittedCities);

        session()->put('submittedCities', $this->submittedCities);
    }

    public function render()
    {
        return view('livewire.staff.staff-cities');
    }
}
