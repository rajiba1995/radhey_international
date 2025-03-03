<div class="row mb-4">
    <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            @if(session()->has('message'))
                                <div class="alert alert-success" id="flashMessage">
                                    {{ session('message') }}
                                </div>
                            @endif
                        </div>
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-7">
                                <h6>Salesman Bill Book</h6>
                            </div>
                            <div class="col-lg-6 col-5 my-auto text-end">
                                <div class="input-group w-100 search-input-group">
                                    <input type="text" wire:model.debounce.500ms="search" class="form-control border border-2 p-2 custom-input-sm" placeholder="Enter Name">
                                    <button type="button" wire:target="search" class="btn btn-dark text-light mb-0 custom-input-sm">
                                        <span class="material-icons">search</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                        Date & Time
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                        Salesman
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                        Bill Book Info
                                    </th>
                                   
                                    <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody> 
                                @if ($billings->count()>0)
                                      @foreach($billings as  $billing)
                                    <tr>
                                        <td>
                                            {{date('d M Y',strtotime($billing->created_at))}}
                                        </td>
                                        <td class="align-middle text-center">{{ $billing->salesman? $billing->salesman->name : ""}}</td>
                                        <td class="align-middle text-center">
                                            <ul>
                                               <li>   Start No: {{ $billing->start_no}}</li>
                                              <li>    End No:{{ $billing->end_no}}</li>
                                               <li>   Total Bill:  <span class="text-success">{{ $billing->total_count}}</span></li>
                                                <li>  No Of Used Bill: <span class="text-danger">{{ $billing->no_of_used}}</span></li>
                                            </ul>
                                         
                                        </td>
                                        <td class="align-middle text-end px-4">
                                            <button wire:click="edit({{ $billing->id }})" class="btn btn-outline-info btn-sm custom-btn-sm" title="Edit">
                                                <span class="material-icons">edit</span>
                                            </button>
                                            <button wire:click="destroy({{ $billing->id }})" class="btn btn-outline-danger btn-sm custom-btn-sm" title="Delete">
                                                <span class="material-icons">delete</span>
                                            </button>
                                            @if ($billing->no_of_used != $billing->total_count )
                                                <button wire:click="assignToNewSalesman({{ $billing->id }})" class="btn btn-outline-info btn-sm custom-btn-sm" title=" Assigned new Salesman">
                                                    Assigned new Salesman
                                                </button>
                                            @endif
                                            
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                              
                            </tbody>
                        </table>

                            <div class="d-flex justify-content-end mt-2">
                                {{-- {{ $categories->links() }} --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-md-0 mb-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    {{-- for new salesman --}}
                    @if ($assign_new_salesman)
                    <div class="card-body px-0 pb-2 mx-4">
                        <div class="d-flex justify-content-between mb-3">
                            <h5>{{$assign_new_salesman ? "Assigned New Salesman" : ""}}</h5>  
                        </div>
                        <form wire:submit.prevent="SubmitNewSalesman">
                            <div class="row">

                                <label class="form-label mt-3">Salesman</label>
                                <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                   
                                        <select wire:model="salesman_id" class="form-control border border-2 p-2">
                                            <option value="" selected hidden>Select Salesman</option>
                                            @foreach ($salesmans as $salesman)
                                            @if ($salesman->id != $salesman_id)
                                              <option value="{{$salesman->id}}">{{$salesman->name}}</option> 
                                            @endif
                                            @endforeach
                                        </select>
                                   
                                </div>
                                @error('salesman_id')
                                    <p class='text-danger inputerror'>{{ $message }}</p>
                                @enderror

                                {{-- short code --}}
                                <label class="form-label">Start Billing Number</label>
                                <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                    <input type="number" wire:model="start_no" class="form-control border border-2 p-2" placeholder="Enter Start Billing Number">
                                </div>
                                @error('start_no')
                                    <p class='text-danger inputerror'>{{ $message }}</p>
                                @enderror

                                <label class="form-label">End Billing Number</label>
                                <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                    <input type="number" wire:model="end_no" class="form-control border border-2 p-2" placeholder="Enter End Billing Number">
                                </div>
                                @error('end_no')
                                    <p class='text-danger inputerror'>{{ $message }}</p>
                                @enderror

                                <div class="mb-2 text-end mt-4">
                                    <button type="submit" class="btn btn-cta btn-sm mt-1" wire:loading.attr="disabled">
                                        <span>Assign New Salesman</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                    {{-- end new salesman --}}
                    @else

                    <div class="card-body px-0 pb-2 mx-4">
                        <div class="d-flex justify-content-between mb-3">
                            <h5>{{$billing_id ? "Update Bill Book" : "Create Bill Book"}}</h5>  
                        </div>
                        <form wire:submit.prevent="{{$billing_id ? "update" : "submit"}}">
                            <div class="row">

                                <label class="form-label mt-3">Salesman</label>
                                <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                    @if($assign_new_salesman && $salesman_id) 
                                        <!-- Show the assigned salesman's name -->
                                        <span class="form-control border border-2 p-2">{{ $salesmans->find($salesman_id)->name }}</span>
                                    @else
                                        <select wire:model="salesman_id" class="form-control border border-2 p-2">
                                            <option value="" selected hidden>Select Salesman</option>
                                            @foreach ($salesmans as $salesman)
                                                <option value="{{$salesman->id}}">{{$salesman->name}}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                @error('salesman_id')
                                    <p class='text-danger inputerror'>{{ $message }}</p>
                                @enderror

                                {{-- short code --}}
                                <label class="form-label">Start Billing Number</label>
                                <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                    <input type="number" wire:model="start_no" class="form-control border border-2 p-2" placeholder="Enter Start Billing Number">
                                </div>
                                @error('start_no')
                                    <p class='text-danger inputerror'>{{ $message }}</p>
                                @enderror

                                <label class="form-label">End Billing Number</label>
                                <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                    <input type="number" wire:model="end_no" class="form-control border border-2 p-2" placeholder="Enter End Billing Number">
                                </div>
                                @error('end_no')
                                    <p class='text-danger inputerror'>{{ $message }}</p>
                                @enderror

                                <div class="mb-2 text-end mt-4">
                                    <a href="" class="btn btn-cta btn-sm mt-1">
                                    <i class="material-icons text-white" style="font-size: 15px;">refresh</i> 
                                        Refresh</a>
                                    <button type="submit" class="btn btn-cta btn-sm mt-1" 
                                            wire:loading.attr="disabled">
                                        <span> 
                                            {{$billing_id ? "Update Bill Book" : "Create Bill Book"}}
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
