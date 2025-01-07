<div>
    <div class="content-wrapper">
{{-- @dd($order) --}}
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
  <div class="d-flex flex-column justify-content-center">
    <div class="d-flex align-items-center mb-1">
      <h5 class="mb-0">Order {{$order->order_number}}</h5>
        <!-- Paid Status -->
        @if ($order->remaining_amount == 0)
            <span class="badge bg-success me-2 ms-2 rounded-pill">Paid</span>
        @else    
            <span class="badge bg-warning me-2 ms-2 rounded-pill">Pending</span>
        @endif
        @if ($order->status = 1)
          <span class="badge bg-info rounded-pill">Ready to Pickup</span>   
        @endif
    </div>
    <p class="mt-1 mb-3">
        {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }},
        <span id="orderYear">{{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }} (ET)</span>
    </p>
  </div>
  <div class="d-flex align-content-center flex-wrap gap-2">
    <button class="btn btn-outline-danger delete-order waves-effect" data-id="{{$order->id}}">Delete Order</button>
  </div>
</div>

<!-- Order Details Table -->

<div class="row">
  <div class="col-12 col-lg-8">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0">Order details</h5>
        <h6 class="m-0"><a href="javascript:void(0)">Edit</a></h6>
      </div>
      <div class="card-datatable table-responsive">
        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer"><table class="datatables-order-details table dataTable no-footer dtr-column" id="DataTables_Table_0" style="width: 737px;">
          <thead>
            <tr>
                <th class="control sorting_disabled dtr-hidden" rowspan="1" colspan="1" style="width: 0px; display: none;"aria-label="">
                </th>
                <th class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all" rowspan="1" colspan="1"    style="width: 18px;" data-col="1" aria-label=""><input type="checkbox" class="form-check-input">
                </th>
                <th class="w-50 sorting_disabled" rowspan="1" colspan="1" style="width: 328px;" aria-label="products">products</th>
                <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 65px;" aria-label="price">price</th>
                <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 50px;" aria-label="qty">qty</th>
                <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 80px;" aria-label="total">total</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($orderItems as $item)
            {{-- @dd($item); --}}
                <tr class="odd">
                    <td class="  control" tabindex="0" style="display: none;"></td>
                    <td class="  dt-checkboxes-cell"><input type="checkbox" class="dt-checkboxes form-check-input"></td>
                    <td class="sorting_1">
                        <div class="d-flex justify-content-start align-items-center product-name">
                        <div class="avatar-wrapper me-3">
                            @if (!empty($item['product_image']))
                                <div class="avatar avatar-sm rounded-2 bg-label-secondary">
                                    <img src="{{ asset('storage/' . $item['product_image']) }}" alt="Product Image" class="rounded-2">
                                </div>
                            @else
                                <div class="avatar avatar-sm rounded-2 bg-label-secondary">
                                    <img src="{{asset('assets/img/cubes.png')}}" alt="Default Image" class="rounded-2">
                                </div>
                            @endif
                        </div>

                        <div class="d-flex flex-column">
                            <span class="text-nowrap text-heading fw-medium">{{$item['product_name']}}</span>
                        </div>
                    </div>
                    </td>
                    <td><span>{{number_format($item['price'], 2)}}</span></td>
                    <td><span>1</span></td>
                    <td><span>{{number_format($item['price'], 2)}}</span></td>
                </tr>
            @endforeach
           
            </tbody>
        </table><div style="width: 1%;"></div></div>
        <div class="d-flex justify-content-end align-items-center m-4 p-1 mb-0 pb-0">
          <div class="order-calculations">
            <div class="d-flex justify-content-start gap-4 mb-2">
              <span class="w-px-100 text-heading">Subtotal:</span>
              <h6 class="mb-0">$5000.25</h6>
            </div>
            <div class="d-flex justify-content-start gap-4 mb-2">
              <span class="w-px-100 text-heading">Discount:</span>
              <h6 class="mb-0">$00.00</h6>
            </div>
            <div class="d-flex justify-content-start gap-4">
              <h6 class="w-px-100 mb-0">Total:</h6>
              <h6 class="mb-0">$5100.25</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card mb-4">
      <div class="card-header">
        <h5 class="card-title m-0">Shipping activity</h5>
      </div>
      <div class="card-body mt-3">
        <ul class="timeline pb-0 mb-0">
          <li class="timeline-item timeline-item-transparent border-primary">
            <span class="timeline-point timeline-point-primary"></span>
            <div class="timeline-event">
              <div class="timeline-header mb-1">
                <h6 class="mb-0">Order was placed (Order ID: #32543)</h6>
                <small class="text-muted">Tuesday 11:29 AM</small>
              </div>
              <p class="mt-1 mb-3">Your order has been placed successfully</p>
            </div>
          </li>
          <li class="timeline-item timeline-item-transparent border-primary">
            <span class="timeline-point timeline-point-primary"></span>
            <div class="timeline-event">
              <div class="timeline-header mb-1">
                <h6 class="mb-0">Pick-up</h6>
                <small class="text-muted">Wednesday 11:29 AM</small>
              </div>
              <p class="mt-1 mb-3">Pick-up scheduled with courier</p>
            </div>
          </li>
          <li class="timeline-item timeline-item-transparent border-primary">
            <span class="timeline-point timeline-point-primary"></span>
            <div class="timeline-event">
              <div class="timeline-header mb-1">
                <h6 class="mb-0">Dispatched</h6>
                <small class="text-muted">Thursday 11:29 AM</small>
              </div>
              <p class="mt-1 mb-3">Item has been picked up by courier</p>
            </div>
          </li>
          <li class="timeline-item timeline-item-transparent border-primary">
            <span class="timeline-point timeline-point-primary"></span>
            <div class="timeline-event">
              <div class="timeline-header mb-1">
                <h6 class="mb-0">Package arrived</h6>
                <small class="text-muted">Saturday 15:20 AM</small>
              </div>
              <p class="mt-1 mb-3">Package arrived at an Amazon facility, NY</p>
            </div>
          </li>
          <li class="timeline-item timeline-item-transparent">
            <span class="timeline-point timeline-point-primary"></span>
            <div class="timeline-event">
              <div class="timeline-header mb-1">
                <h6 class="mb-0">Dispatched for delivery</h6>
                <small class="text-muted">Today 14:12 PM</small>
              </div>
              <p class="mt-1 mb-3">Package has left an Amazon facility, NY</p>
            </div>
          </li>
          <li class="timeline-item timeline-item-transparent border-transparent pb-0">
            <span class="timeline-point timeline-point-secondary"></span>
            <div class="timeline-event pb-0">
              <div class="timeline-header mb-1">
                <h6 class="mb-0">Delivery</h6>
              </div>
              <p class="mt-1 mb-3">Package will be delivered by tomorrow</p>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-4">
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title mb-6">Customer details</h5>
        <div class="d-flex justify-content-start align-items-center mb-6">
          <div class="avatar me-3">
            <img src="https://demos.themeselection.com/materio-bootstrap-html-laravel-admin-template/demo/assets/img/avatars/1.png" alt="Avatar" class="rounded-circle">
          </div>
          <div class="d-flex flex-column">
            <a href="https://demos.themeselection.com/materio-bootstrap-html-laravel-admin-template/demo-1/app/user/view/account">
              <h6 class="mb-0">Shamus Tuttle</h6>
            </a>
            <span>Customer ID: #58909</span></div>
        </div>
        <div class="d-flex justify-content-start align-items-center mb-6">
          <span class="avatar rounded-circle bg-label-success me-3 d-flex align-items-center justify-content-center"><i class="ri-shopping-cart-line ri-24px"></i></span>
          <h6 class="text-nowrap mb-0">12 Orders</h6>
        </div>
        <div class="d-flex justify-content-between">
          <h6 class="mb-1">Contact info</h6>
          {{-- <h6 class="mb-1"><a href=" javascript:;" data-bs-toggle="modal" data-bs-target="#editUser">Edit</a></h6> --}}
        </div>
        <p class="mb-1">Email: Shamus889@yahoo.com</p>
        <p class="mb-0">Mobile: +1 (609) 972-22-22</p>
      </div>
    </div>

    <div class="card mb-4">

      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-1">Shipping address</h5>
        {{-- <h6 class="m-0"><a href=" javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addNewAddress">Edit</a></h6> --}}
      </div>
      <div class="card-body">
        <p class="mb-0">45 Roker Terrace <br>Latheronwheel <br>KW5 8NW,London <br>UK</p>
      </div>

    </div>
    <div class="card mb-6">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-1">Billing address</h5>
        {{-- <h6 class="m-0"><a href=" javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addNewAddress">Edit</a></h6> --}}
      </div>
      <div class="card-body">
        <p class="mb-6">45 Roker Terrace <br>Latheronwheel <br>KW5 8NW,London <br>UK</p>
        <h5 class="mb-0 pb-1">Mastercard</h5>
        <p class="mb-0">Card Number: ******4291</p>
      </div>

    </div>
  </div>
</div>

<!-- Modals -->
<!-- Edit User Modal -->
<div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-6">
          <h4 class="mb-2">Edit User Information</h4>
          <p class="mb-6">Updating user details will receive a privacy audit.</p>
        </div>
        <form id="editUserForm" class="row g-5 fv-plugins-bootstrap5 fv-plugins-framework" onsubmit="return false" novalidate="novalidate">
          <div class="col-12 col-md-6 fv-plugins-icon-container">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalEditUserFirstName" name="modalEditUserFirstName" class="form-control" value="Oliver" placeholder="Oliver">
              <label for="modalEditUserFirstName">First Name</label>
            </div>
          <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
          <div class="col-12 col-md-6 fv-plugins-icon-container">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalEditUserLastName" name="modalEditUserLastName" class="form-control" value="Queen" placeholder="Queen">
              <label for="modalEditUserLastName">Last Name</label>
            </div>
          <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
          <div class="col-12 fv-plugins-icon-container">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalEditUserName" name="modalEditUserName" class="form-control" value="oliver.queen" placeholder="oliver.queen">
              <label for="modalEditUserName">Username</label>
            </div>
          <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalEditUserEmail" name="modalEditUserEmail" class="form-control" value="oliverqueen@gmail.com" placeholder="oliverqueen@gmail.com">
              <label for="modalEditUserEmail">Email</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="modalEditUserStatus" name="modalEditUserStatus" class="form-select" aria-label="Default select example">
                <option value="1" selected="">Active</option>
                <option value="2">Inactive</option>
                <option value="3">Suspended</option>
              </select>
              <label for="modalEditUserStatus">Status</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalEditTaxID" name="modalEditTaxID" class="form-control modal-edit-tax-id" placeholder="123 456 7890">
              <label for="modalEditTaxID">Tax ID</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="input-group input-group-merge">
              <span class="input-group-text">US (+1)</span>
              <div class="form-floating form-floating-outline">
                <input type="text" id="modalEditUserPhone" name="modalEditUserPhone" class="form-control phone-number-mask" value="+1 609 933 4422" placeholder="+1 609 933 4422">
                <label for="modalEditUserPhone">Phone Number</label>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline form-floating-select2">
              <div class="position-relative"><div class="position-relative"><select id="modalEditUserLanguage" name="modalEditUserLanguage" class="select2 form-select select2-hidden-accessible" multiple="" tabindex="-1" aria-hidden="true" data-select2-id="modalEditUserLanguage">
                <option value="">Select</option>
                <option value="english" selected="" data-select2-id="18">English</option>
                <option value="spanish">Spanish</option>
                <option value="french">French</option>
                <option value="german">German</option>
                <option value="dutch">Dutch</option>
                <option value="hebrew">Hebrew</option>
                <option value="sanskrit">Sanskrit</option>
                <option value="hindi">Hindi</option>
              </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="17" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--multiple" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1" aria-disabled="false"><ul class="select2-selection__rendered"><li class="select2-selection__choice" title="English" data-select2-id="19"><span class="select2-selection__choice__remove" role="presentation">×</span>English</li><li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="searchbox" aria-autocomplete="list" placeholder="" style="width: 0.75em;"></li></ul></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span></div></div>
              <label for="modalEditUserLanguage">Language</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline form-floating-select2">
              <div class="position-relative"><div class="position-relative"><select id="modalEditUserCountry" name="modalEditUserCountry" class="select2 form-select select2-hidden-accessible" data-allow-clear="true" tabindex="-1" aria-hidden="true" data-select2-id="modalEditUserCountry">
                <option value="">Select</option>
                <option value="Australia">Australia</option>
                <option value="Bangladesh">Bangladesh</option>
                <option value="Belarus">Belarus</option>
                <option value="Brazil">Brazil</option>
                <option value="Canada">Canada</option>
                <option value="China">China</option>
                <option value="France">France</option>
                <option value="Germany">Germany</option>
                <option value="India" selected="" data-select2-id="46">India</option>
                <option value="Indonesia">Indonesia</option>
                <option value="Israel">Israel</option>
                <option value="Italy">Italy</option>
                <option value="Japan">Japan</option>
                <option value="Korea">Korea, Republic of</option>
                <option value="Mexico">Mexico</option>
                <option value="Philippines">Philippines</option>
                <option value="Russia">Russian Federation</option>
                <option value="South Africa">South Africa</option>
                <option value="Thailand">Thailand</option>
                <option value="Turkey">Turkey</option>
                <option value="Ukraine">Ukraine</option>
                <option value="United Arab Emirates">United Arab Emirates</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="United States">United States</option>
              </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="45" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-modalEditUserCountry-container"><span class="select2-selection__rendered" id="select2-modalEditUserCountry-container" role="textbox" aria-readonly="true" title="India"><span class="select2-selection__clear" title="Remove all items" data-select2-id="47">×</span>India</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span></div></div>
              <label for="modalEditUserCountry">Country</label>
            </div>
          </div>
          <div class="col-12">
            <div class="form-check form-switch">
              <input type="checkbox" class="form-check-input" id="editBillingAddress">
              <label for="editBillingAddress" class="text-heading">Use as a billing address?</label>
            </div>
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-3 waves-effect waves-light">Submit</button>
            <button type="reset" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        <input type="hidden"></form>
      </div>
    </div>
  </div>
</div>
<!--/ Edit User Modal -->
<!-- Add New Address Modal -->
<div class="modal fade" id="addNewAddress" tabindex="-1" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-6">
          <h4 class="address-title mb-2">Add New Address</h4>
          <p class="address-subtitle">Add new address for express delivery</p>
        </div>
        <form id="addNewAddressForm" class="row g-5 fv-plugins-bootstrap5 fv-plugins-framework" onsubmit="return false" novalidate="novalidate">

          <div class="col-12">
            <div class="row g-5">
              <div class="col-md mb-md-0">
                <div class="form-check custom-option custom-option-basic checked">
                  <label class="form-check-label custom-option-content" for="customRadioHome">
                    <input name="customRadioTemp" class="form-check-input" type="radio" value="" id="customRadioHome" checked="">
                    <span class="custom-option-header">
                      <span class="h6 mb-0 d-flex align-items-center"><i class="ri-home-smile-2-line ri-20px me-1"></i>Home</span>
                    </span>
                    <span class="custom-option-body">
                      <small>Delivery time (9am – 9pm)</small>
                    </span>
                  </label>
                </div>
              </div>
              <div class="col-md mb-md-0">
                <div class="form-check custom-option custom-option-basic">
                  <label class="form-check-label custom-option-content" for="customRadioOffice">
                    <input name="customRadioTemp" class="form-check-input" type="radio" value="" id="customRadioOffice">
                    <span class="custom-option-header">
                      <span class="h6 mb-0 d-flex align-items-center"><i class="ri-building-line ri-20px me-1"></i>Office</span>
                    </span>
                    <span class="custom-option-body">
                      <small>Delivery time (9am – 5pm) </small>
                    </span>
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 fv-plugins-icon-container">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalAddressFirstName" name="modalAddressFirstName" class="form-control" placeholder="John">
              <label for="modalAddressFirstName">First Name</label>
            </div>
          <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
          <div class="col-12 col-md-6 fv-plugins-icon-container">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalAddressLastName" name="modalAddressLastName" class="form-control" placeholder="Doe">
              <label for="modalAddressLastName">Last Name</label>
            </div>
          <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
          <div class="col-12">
            <div class="form-floating form-floating-outline form-floating-select2">
              <div class="position-relative"><div class="position-relative"><select id="modalAddressCountry" name="modalAddressCountry" class="select2 form-select select2-hidden-accessible" data-allow-clear="true" tabindex="-1" aria-hidden="true" data-select2-id="modalAddressCountry">
                <option value="" data-select2-id="74">Select</option>
                <option value="Australia">Australia</option>
                <option value="Bangladesh">Bangladesh</option>
                <option value="Belarus">Belarus</option>
                <option value="Brazil">Brazil</option>
                <option value="Canada">Canada</option>
                <option value="China">China</option>
                <option value="France">France</option>
                <option value="Germany">Germany</option>
                <option value="India">India</option>
                <option value="Indonesia">Indonesia</option>
                <option value="Israel">Israel</option>
                <option value="Italy">Italy</option>
                <option value="Japan">Japan</option>
                <option value="Korea">Korea, Republic of</option>
                <option value="Mexico">Mexico</option>
                <option value="Philippines">Philippines</option>
                <option value="Russia">Russian Federation</option>
                <option value="South Africa">South Africa</option>
                <option value="Thailand">Thailand</option>
                <option value="Turkey">Turkey</option>
                <option value="Ukraine">Ukraine</option>
                <option value="United Arab Emirates">United Arab Emirates</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="United States">United States</option>
              </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="73" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-modalAddressCountry-container"><span class="select2-selection__rendered" id="select2-modalAddressCountry-container" role="textbox" aria-readonly="true"><span class="select2-selection__placeholder">Select value</span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span></div></div>
              <label for="modalAddressCountry">Country</label>
            </div>
          </div>
          <div class="col-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalAddressAddress1" name="modalAddressAddress1" class="form-control" placeholder="12, Business Park">
              <label for="modalAddressAddress1">Address Line 1</label>
            </div>
          </div>
          <div class="col-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalAddressAddress2" name="modalAddressAddress2" class="form-control" placeholder="Mall Road">
              <label for="modalAddressAddress2">Address Line 2</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalAddressLandmark" name="modalAddressLandmark" class="form-control" placeholder="Nr. Hard Rock Cafe">
              <label for="modalAddressLandmark">Landmark</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalAddressCity" name="modalAddressCity" class="form-control" placeholder="Los Angeles">
              <label for="modalAddressCity">City</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalAddressState" name="modalAddressState" class="form-control" placeholder="California">
              <label for="modalAddressLandmark">State</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalAddressZipCode" name="modalAddressZipCode" class="form-control" placeholder="99950">
              <label for="modalAddressZipCode">Zip Code</label>
            </div>
          </div>
          <div class="col-12">
            <div class="form-check form-switch">
              <input type="checkbox" class="form-check-input" id="billingAddress">
              <label for="billingAddress">Use as a billing address?</label>
            </div>
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-3 waves-effect waves-light">Submit</button>
            <button type="reset" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        <input type="hidden"></form>
      </div>
    </div>
  </div>
</div>
<!--/ Add New Address Modal -->
          </div>
          <!-- / Content -->  
    </div>
</div>