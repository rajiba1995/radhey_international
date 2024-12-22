<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
        <form wire:submit.prevent="submit" class="space-y-4">
            <!-- Title -->
            <h3 class="text-xl font-semibold text-gray-700">Select Your City</h3>

            <!-- City Select Box -->
            <div>
                <label for="city" class="block text-sm font-medium text-gray-600">City</label>
                <select id="city" wire:model="selectedCity"
                    class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">-- Choose a City --</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>
            @error('selectedCity')
                <div class="text-danger">{{ $message }}</div>
            @enderror
           

            @if (!empty($submittedCities))
            <div class="mt-4 p-2 bg-blue-100 text-blue-700 rounded-md text-sm">
                <p>You selected:</p>
                <ul>
                    @foreach ($submittedCities as $city)
                        <li class="flex justify-between items-center">
                            {{ $city }}
                            <span wire:click="deleteCity('{{ $city }}')" class="cursor-pointer text-red-600 hover:text-red-800 ml-2">
                                ❌
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

            <!-- Buttons -->
            <div class="flex justify-between items-center mt-4">
                <button type="submit"
                    class="btn btn-primary px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                    Submit
                </button>
                <button type="button" onclick="window.history.back();"
                    class="btn btn-dark px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                    Back
                </button>
            </div>
        </form>

        <!-- Display Messages -->
        @if (session()->has('message'))
            <div class="mt-4 p-2 bg-green-100 text-green-700 rounded-md text-sm">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mt-4 p-2 bg-red-100 text-red-700 rounded-md text-sm">
                {{ session('error') }}
            </div>
        @endif
    </div>
</div>
