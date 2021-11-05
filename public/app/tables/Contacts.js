// "use strict";
// Class definition

var KTDatatableRecordSelectionDemo = function() {
    // Private functions

    var options = {
        // datasource definition
        data: {
            type: 'remote',
            source: {
                read: {
                    url: DATA_URL,
                    method: "GET",
                    contentType: 'application/json',
                },
            },
            pageSize: 20,
            serverPaging: true,
            serverFiltering: true,
            serverSorting: true,
        },

        // layout definition
        layout: {
            scroll: false, // enable/disable datatable scroll both horizontal and
            footer: false // display/hide footer
        },

        // column sorting
        sortable: true,

        // search: {
        //     input: $('#kt_datatable_search_query'),
        //     key: 'generalSearch'
        // },

        pagination: true,

        // columns definition
        columns: [
        {
            field: "ID",
            title: "#",
            template: "{{id}}",
            width: 40,
            autoHide: false,
        }, 
        {
            field: 'Name',
            title: 'Name',
            sortable: true,
            autoHide: false,
            width: 200,
            template: function(data) {
                
                image = 'background-image:url(\'/src/images/' + data.images["main"] +'\')';

                var output = '<a href="/a/Contacts/view/'+ data.id +'"><div class="d-flex align-items-center">\
                        <div class="symbol symbol-40 flex-shrink-0">\
                            <div class="symbol-label" style="' + image + '"></div>\
                        </div>\
                        <div class="ml-2">\
                            <div class="text-dark-75 font-weight-bold line-height-sm" style="white-space: nowrap;">' + data.name + '</div>\
                            <span class="font-size-sm text-dark-50 text-hover-primary">' +
                            data.brand + '</span>\
                        </div>\
                    </div></a>';

                return output;
            }

        }, {
            field: 'Members',
            title: 'Members',
            width: 40,
            autoHide: false,
            template: "{{members}}"
        }, {
            field: 'CreatedDate',
            title: 'Created Date',
            template: "{{created_date}}"
        }, {
            field: 'UpdatedDate',
            title: 'Last Updated',
            template: "{{updated_date}}"
        }, {
            field: 'Actions',
            title: 'Actions',
            sortable: false,
            width: 130,
            overflow: 'visible',
            textAlign: 'right',
	        autoHide: false,
            template: function(row) {

                var editButton = '\
                    <a href="/contacts/details/'+ row.id +'" onclick="api_edit(this)" api-edit="Contact" api-item-id="'+ row.id +'" type="button" data-toggle="modal" data-target="#editContact" class="btn btn-sm btn-clean btn-light-success btn-icon mr-2" title="Edit details">\
                        <span class="svg-icon svg-icon-md">\
                            <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                    <rect x="0" y="0" width="24" height="24"/>\
                                    <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero"\ transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>\
                                    <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>\
                                </g>\
                            </svg>\
                        </span>\
                    </a>';

      
                

                var DeleteButton = '<a href="/contact/details/'+ row.id +'" onclick="api_delete(this);" api-delete="Contact" api-item-id="'+ row.id +'" type="button" data-toggle="modal" data-target="#deleteContact" class="btn btn-sm btn-clean btn-light-danger btn-icon" title="Delete">\
                            <span class="svg-icon svg-icon-md">\
                                <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                        <rect x="0" y="0" width="24" height="24"/>\
                                        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>\
                                        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>\
                                    </g>\
                                </svg>\
                            </span>\
                        </a>';

                
                var manageActions = "";
                
                if(row.status != 0) 
                {
                    var actionButton = '<li class="navi-item">\
                        <a href="/api/contact/details/'+ row.id +'" onclick="api_edit(this);" api-edit="Contact" api-item-id="'+ row.id +'" type="button" data-toggle="modal" data-target="#changeType" class="navi-link">\
                            <span class="navi-icon"><i class="flaticon-refresh"></i></span>\
                            <span class="navi-text">Change Contact Type</span>\
                        </a>\
                    </li>';
                    
                    // if(row.type == 2) {
                    //     actionButton += '<li class="navi-item">\
                    //         <a href="/a/Contact/update-variations/'+ row.id +'" class="navi-link">\
                    //             <span class="navi-icon"><i class="flaticon-layers"></i></span>\
                    //             <span class="navi-text">Update Variations</span>\
                    //         </a>\
                    //     </li>';
                    // }
                    // else if(row.type == 1)
                    // {
                    //     actionButton += '<li class="navi-item">\
                    //         <a href="/a/Contact/update-gallery/'+ row.id +'" class="navi-link">\
                    //             <span class="navi-icon"><i class="la la-user-times"></i></span>\
                    //             <span class="navi-text">Update Gallery</span>\
                    //         </a>\
                    //     </li>';
                    // }
    
                    var stateButton = '<li class="navi-item">\
                            <a href="/api/contact/details/'+ row.id +'" onclick="api_delete(this);" api-delete="Contact" api-item-id="'+ row.id +'" type="button" data-toggle="modal" data-target="#hideContact" class="navi-link">\
                                <span class="navi-icon"><i class="flaticon-alert-off"></i></span>\
                                <span class="navi-text">Hide Contact</span>\
                            </a>\
                        </li>';

                    if(row.status == 4) 
                    {
                        stateButton = '<li class="navi-item">\
                            <a href="/api/contact/details/'+ row.id +'" onclick="api_delete(this);" api-delete="Contact" api-item-id="'+ row.id +'" type="button" data-toggle="modal" data-target="#showContact" class="navi-link">\
                                <span class="navi-icon"><i class="flaticon-alert"></i></span>\
                                <span class="navi-text">Show Contact</span>\
                            </a>\
                        </li>';
                    }

                    manageActions = '<div class="dropdown dropdown-inline">\
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-light-dark btn-icon mr-2" data-toggle="dropdown">\
                            <span class="svg-icon svg-icon-md">\
                                <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                        <rect x="0" y="0" width="24" height="24"/>\
                                        <path d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z" fill="#000000"/>\
                                    </g>\
                                </svg>\
                            </span>\
                        </a>\
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">\
                            <ul class="navi flex-column navi-hover py-2">\
                                <li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">\
                                    Choose an action:\
                                </li>\
                                <li class="navi-item">\
                                    <a href="javascript:;" onclick="api_edit(this);" type="button" data-toggle="modal" data-target="#addContact" class="navi-link">\
                                        <span class="navi-icon"><i class="flaticon-interface-6"></i></span>\
                                        <span class="navi-text">Add Contact</span>\
                                    </a>\
                                </li>'+ actionButton + stateButton +'\
                            </ul>\
                        </div>\
                    </div>';
                }

                return '\
                        <a href="/contacts/list/'+ row.id +'" class="btn btn-primary btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">\
                            <span class="svg-icon svg-icon-md">\
                                <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                        <rect x="0" y="0" width="24" height="24"/>\
                                        <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>\
                                        <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>\
                                    </g>\
                                </svg>\
                            </span>\
                        </a>' + editButton +  ((row.status == 2) ? DeleteButton : manageActions);
            },
        }],
    };

    // basic demo
    var localSelectorDemo = function() {
        // enable extension
        options.extensions = {
            // boolean or object (extension options)
            checkbox: true,
        };

        options.search = {
            input: $('#kt_datatable_search_query'),
            key: 'generalSearch'
        };

        var datatable = $('#Contacts').KTDatatable(options);

        $('#kt_datatable_search_status').on('change', function() {
            window.location.href = "/contacts" + $(this).val();
        });

        $('#kt_datatable_search_status, #kt_datatable_search_type').selectpicker();
    };


    return {
        // public functions
        init: function() {
            localSelectorDemo();
        },
    };
}();

jQuery(document).ready(function() {
    KTDatatableRecordSelectionDemo.init();
});