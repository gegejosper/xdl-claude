const customerSearchKey = document.getElementById('search_customers');
const customersList = document.querySelector('.customers-list');
function buildCustomer(customers){
    if(customers.length != 0){
        customersList.innerHTML = customers.map((customer, i) => {
            let classAdd ='';
            let blocked_btn =''
            if(customer.status === 'active'){
                classAdd = 'badge-light-success';
            }
            else {
                classAdd = 'badge-light-danger';
            }
            if(customer.status === 'active')
            blocked_btn =` 
                <a href="javascript:;" id="blockcustomer${customer.id}" class="btn btn-icon btn-active-light-warning block-customer"
                    data-customer_id="${customer.id}"
                    data-customer_status="blocked">
                    <span class="svg-icon svg-icon-muted svg-icon-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"/>
                    <rect x="9" y="13.0283" width="7.3536" height="1.2256" rx="0.6128" transform="rotate(-45 9 13.0283)" fill="currentColor"/>
                    <rect x="9.86664" y="7.93359" width="7.3536" height="1.2256" rx="0.6128" transform="rotate(45 9.86664 7.93359)" fill="currentColor"/>
                    </svg></span>
                </a>`
           
            else if(customer.status === 'blocked'){
                blocked_btn =` <a href="javascript:;" id="blockcustomer${customer.id}" class="btn btn-icon btn-active-light-info block-customer"
                data-customer_id="${customer.id}"
                data-customer_status="active">
                <span class="svg-icon svg-icon-muted svg-icon-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"/>
                <path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24176 14.7183 8.24176 14.4272 8.53714L11.7002 11.3042C11.3869 11.6221 10.874 11.6221 10.5606 11.3042Z" fill="currentColor"/>
                </svg></span>
            </a>`;
            }
            else {
                blocked_btn = '';
            }
           
            return `
            <tr>
                <td>
                    <a href="/panel/customers/${customer.id}" class="text-gray-800 text-hover-primary mb-1">${customer.last_name}, ${customer.first_name}</a>
                </td>
                <td>
                    ${customer.mobile_num}
                </td>
                <td>${customer.branch_details.branch_name}</td>
                <td>
                    ${customer.address}, ${customer.brgy}, ${customer.city_num}, ${customer.province}
                </td>
                <td>
                    <span id="customer_status_${customer.id}" class="badge ${classAdd}">${customer.status}</span>
                   
                </td>
                <td class="text-end">
                    <a href="javascript:;" id="customer_edit_${customer.id}" class="btn btn-icon btn-active-light-info edit-customer"
                        data-customer_id="${customer.id}"
                        data-customer_last_name="${customer.last_name}"
                        data-customer_first_name="${customer.first_name}"
                        data-customer_mobile_num="${customer.mobile_num}"
                        data-customer_address="${customer.address}"
                        data-customer_facebook="${customer.facebook}"
                    >
                            <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                            </svg>
                        </span>
                    </a>
                    ${blocked_btn}
                    <a href="/panel/customers/${customer.id}" class="btn btn-icon btn-active-light-success">
                        <i class="fas fa-search"></i>
                    </a>
                </td>
            </tr>
              `;
        }).join('');  
    }
    else {
        customersList.innerHTML = "<tr><td class='text-danger'><em>No results found...</em></td></tr>"; 
    }
}
function searchCustomers(){
    $.ajax({
        type: 'post',
        url: '/panel/customers/search',
        data: {
            //_token:$(this).data('token'),
            '_token': $('input[name=_token]').val(),
            'search_query': $('input[name=search_customers]').val() 
        },
        success: function(customers) {
            buildCustomer(customers);   
        },
        error: function(data){
          var errors = data.responseJSON.errors;
          var errormessage = '';
          Object.keys(errors).forEach(function(key) {
              errormessage += errors[key] + '<br />';
              $('.errors').html('');
              $('.errors').append(`
              <div class="alert alert-danger" role="alert"> ${errormessage} </div>
              `);
          });
        }
    });
}
if(customerSearchKey){
    customerSearchKey.addEventListener('keyup', searchCustomers);
}
$(document).ready(function() {

    $(document).on('click', '.edit-customer', function() {
        $('#edit_first_name').val($(this).data('customer_first_name'));
        $('#edit_last_name').val($(this).data('customer_last_name'));
        $('#edit_address').val($(this).data('customer_address'));
        $('#edit_mobile_num').val($(this).data('customer_mobile_num'));
        $('#edit_facebook').val($(this).data('customer_facebook'));
        $('#edit_customer_id').val($(this).data('customer_id'));
        $('#modal_edit_customer').modal('show');
    });
    $(document).on('click', '.closemodify', function() {
        location.reload();
    });

    $(document).on('click', '.modify-customer', function() {

        $('#id').val($(this).data('id'));
        $('#customer_modify_id').val($(this).data('customer_id'));
        $('#customer_modify_status').val($(this).data('customer_status'));
        $('#modifycustomerModal').modal('show');
    });
    $('.modal-footer').on('click', '#modifycustomer', function() {
  
        $.ajax({
            type: 'post',
            url: '/panel/customeres/modify',
            data: {
                //_token:$(this).data('token'),
                '_token': $('input[name=_token]').val(),
                'customer_id': $('input[name=customer_modify_id]').val(),
                'customer_status': $('input[name=customer_modify_status]').val()
                
            },
            success: function(data) {
                $('#modifycustomerModal').modal('toggle');
                let status;
                if(data.status === 'active'){
                    status = 'inactive';
                }
                else {
                    status = 'active';
                }
                $('#customer_status_'+data.id).text(data.status);
                $('#modifycustomer' + data.id).data('category_status', status);
                //$('#modifycustomerModalSuccess').modal('show');
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toastr-bottom-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                };
                toastr.success("Category status updated");
            },
            
            error: function(data){
              var errors = data.responseJSON.errors;
              var errormessage = '';
              Object.keys(errors).forEach(function(key) {
                  errormessage += errors[key] + '<br />';
                  $('.errors').html('');
                  $('.errors').append(`
                  <div class="alert alert-danger" role="alert"> ${errormessage} </div>
                  `);
              });
            }
        });
    });
    $(document).on('submit', '#update_customer_form', function(e) {
        e.preventDefault();
    // $("#updatecustomer").click(function(data) {
          $.ajax({
              type: 'post',
              url: '/panel/customers/edit',
              data: {
                  '_token': $('input[name=_token]').val(),
                  'first_name': $('input[name=edit_first_name]').val(),
                  'last_name': $('input[name=edit_last_name]').val(),
                  'address': $('input[name=edit_address]').val(),
                  'province': $('select[name=edit_province]').val(),
                  'city_municipality': $('select[name=edit_city_municipality]').val(),
                  'barangay': $('select[name=edit_barangay]').val(),
                  'mobile_num': $('input[name=edit_mobile_num]').val(),
                  'facebook': $('input[name=edit_facebook]').val(),
                  'customer_id': $('input[name=edit_customer_id]').val()
              },
              success: function(data) {
                $('#modal_edit_customer').modal('toggle');
                $('#customerUpdateModalSuccess').modal('show');
                
              },
              error: function(data){
                const errorContainer = document.getElementById('errors');
                let errors = data.responseJSON.errors;
                let errormessage = '';
                Object.keys(errors).forEach(function(key) {
                    errormessage += errors[key] + '<br />'; 
                });
                errorContainer.innerHTML = ` <div class="alert alert-danger" role="alert"> ${errormessage} </div>`;
                errorContainer.hidden = false;
              }
  
          });
    });
    $("#addcustomer").click(function(data) {
        $('#addcustomer-cashier-btn').prop('disabled', false);
        $.ajax({
            type: 'post',
            url: '/panel/customers/add',
            data: {
                '_token': $('input[name=_token]').val(),
                'first_name': $('input[name=first_name]').val(),
                'last_name': $('input[name=last_name]').val(),
                'address': $('input[name=address]').val(),
                'barangay': $('select[name=barangay]').val(),
                'branch': $('select[name=branch_id]').val(),
                'province': $('select[name=province]').val(),
                'mobile_num': $('input[name=mobile_num]').val(),
                'city_municipality': $('select[name=city_municipality]').val()
            },
            success: function(data) {
                window.location.replace("/panel/customers/"+data.id);
            }

        });
    });
    $("#addcustomer-cashier").submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: '/panel/customers/add',
            data: {
                '_token': $('input[name=_token]').val(),
                'first_name': $('input[name=first_name]').val(),
                'last_name': $('input[name=last_name]').val(),
                'address': $('input[name=address]').val(),
                'barangay': $('select[name=barangay]').val(),
                'branch': $('select[name=branch]').val(),
                'province': $('select[name=province]').val(),
                'mobile_num': $('input[name=mobile_num]').val(),
                'city_municipality': $('select[name=city_municipality]').val(),
                'facebook': $('input[name=facebook]').val(),
                'email': $('input[name=email]').val()
            },
            success: function(data) {
                customerInfoContainer.hidden = false;
                payeeContainer.hidden = false;
                const name = data.last_name+', '+data.first_name;
                const address = data.address+', '+data.brgy+', '+data.city_num+', '+data.province;
                accountNameEL.value = name;
                $('#customer_name').text(name);
                $('#customer_address').text(address);
                $('#customer_id').val(data.id);
                $('#modal_customer').modal('toggle');
                $('#processinstallment').prop('disabled', false);
                }
            });
    });
});


