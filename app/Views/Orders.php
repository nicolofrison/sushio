<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=0.9, shrink-to-fit=no">

        <title>Login - Sushio</title>
        <meta name="description" content="Sushio login">
        <meta name="author" content="Nicolò Frison">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">

    </head>

    <body class="text-center">
        <div class="container">
            <h1>Orders</h1>
            <div id="errorsText" class="alert alert-danger my-3" style="display: none"></div>
            <div class="list-types d-flex justify-content-around align-items-stretch my-3">
                <button id="listType1" onclick="changeListType(1)" class="col-3 btn btn-sm btn-secondary" type="button">Own orders</button>
                <button id="listType2" onclick="changeListType(2)" class="col-3 btn btn-sm btn-primary" type="button">All orders</button>
                <button id="listType3" onclick="changeListType(3)" class="col-3 btn btn-sm btn-primary" type="button">All orders grouped by code</button>
            </div>
            <table id="ordersTable" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Code</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Users</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <script type="application/javascript" src="js/jquery-3.5.1.min.js"></script>
        <script type="application/javascript" src="js/jquery.md5.js"></script>
        <script>
            $(document).ready(function() {
                retrieveOrders();
                $('#joinGroup').click(function() {
                    if( $('inputName').val() != '' && !$('inputSurname').val() != '' ) {
                        $('#errorsText').hide();
                        $('#step1').hide();
                        $('#step2').show();
                        $('#action').val('joinGroup');
                        $('#submit').text('Join group');
                    } else {
                        $('#errorsText').text('Almost name and surname are needed');
                        $('#errorsText').show();
                    }
                });

                $('#createGroup').click(function() {
                    if( $('inputName').val() != '' && $('inputSurname').val() != '' ) {
                        $('#errorsText').hide();
                        $('#step1').hide();
                        $('#step2').show();
                        $('#action').val('createGroup');
                        $('#submit').text('Create new group');
                    } else {
                        $('#errorsText').text('Almost name and surname are needed');
                        $('#errorsText').show();
                    }
                });

                $('#back').click(function() {
                    $('#step2').hide();
                    $('#step1').show();
                });

                $('#submit').click(function() {
                    login();
                });
            });

            let type = 1;
            function changeListType(newType) {
                $('#listType'+type).removeClass('btn-secondary').addClass('btn-primary').attr("disabled", false);
                $('#listType'+newType).removeClass('btn-primary').addClass('btn-secondary').attr("disabled", true);
                type = newType;
                retrieveOrders();
            }

            function retrieveOrders() {
                let tableBody = '<tr>' +
                    '<td><input type="text" id="inputCode" class="form-control" placeholder="Code" required="" autofocus=""></td>' +
                    '<td><input type="number" id="inputAmount" class="form-control" placeholder="Amount" required="" autofocus=""></td>' +
                    '<td></td>' +
                    '<td><button id="orderButton" onclick="createOrder()" class="btn btn-success btn-block" type="button">Add</button></td>' +
                    '</tr>';

                $.ajax({
                    type: "POST",
                    url: "Orders/getJson/"+type,
                    data: {},
                    success: function(data){
                        if( data.success ) {
                            console.log(data);
                            $('#errorsText').hide();

                            data.data.forEach(row => tableBody += '<tr id="order-'+row.order_id+'" class="orderRow">' +
                                '<td>'+row.code+'</td>' +
                                '<td class="amount">'+row.amount+'</td>' +
                                '<td>'+row.username+'</td>' +
                                '<td class="actions">' +
                                    (type !== 3 ?
                                        '<button class="btn btn-warning btn-block updateOrder" onclick="updateOrder('+row.order_id+')">Edit</button>' +
                                        '<button class="btn btn-danger btn-block deleteOrder" onclick="deleteOrder('+row.order_id+')">Delete</button>' +
                                        '<button class="btn btn-success btn-block saveOrderUpdate d-none" onclick="saveOrderUpdate('+row.order_id+')">Save</button>' +
                                        '<button class="btn btn-danger btn-block undoOrderUpdate d-none" onclick="undoOrderUpdate('+row.order_id+')">Undo</button>'
                                    : '') +
                                '</td>' +
                            '</tr>');

                            $('#ordersTable tbody').html(tableBody);
                        } else {
                            $('#step2').hide();
                            $('#step1').show();
                            $('#errorsText').text(data.message).show();
                        }
                    },
                    error: function(e){
                        console.log(e);
                        alert("<|Server communication error|/>!");
                    }
                });
            }

            function createOrder() {
                let code = $('#inputCode').val().toLowerCase();
                let amount = $('#inputAmount').val();

                $.ajax({
                    type: "POST",
                    url: "Orders/createOrder",
                    data: {
                        'code': code,
                        'amount': amount
                    },
                    success: function(data){
                        if( data.success ) {
                            console.log(data);
                            $('#errorsText').hide();

                            retrieveOrders(type);
                            alert('New order created successfully');
                        } else {
                            $('#errorsText').text(data.message).show();
                        }
                    },
                    error: function(e){
                        console.log(e);
                        alert("<|Server communication error|/>!");
                    }
                });
            }

            function updateOrder(orderId) {
                $('.orderRow').each(function() {
                    let amountTd = $(this).find('.amount');
                    amountTd.html(amountTd.find('.oldAmount').val());
                });
                $('.orderRow .updateOrder').removeClass('d-none');
                $('.orderRow .deleteOrder').removeClass('d-none');
                $('.orderRow .saveOrderUpdate').addClass('d-none');
                $('.orderRow .undoOrderUpdate').addClass('d-none');

                let amountTd = $('#order-'+orderId+' .amount');
                let amount = amountTd.text();
                console.log(amount);

                amountTd.html('<input class="oldAmount" type="hidden" value="'+amount+'"/>' +
                    '<input class="inputEditAmount form-control" type="number" value="'+amount+'"/>');

                $('#order-'+orderId+' .updateOrder').addClass('d-none');
                $('#order-'+orderId+' .deleteOrder').addClass('d-none');
                $('#order-'+orderId+' .saveOrderUpdate').removeClass('d-none');
                $('#order-'+orderId+' .undoOrderUpdate').removeClass('d-none');
            }

            function saveOrderUpdate(orderId) {
                let amount = $('#order-'+orderId+' .inputEditAmount').val();

                $.ajax({
                    type: "POST",
                    url: "Orders/updateOrder",
                    data: {
                        'order_id': orderId,
                        'amount': amount
                    },
                    success: function(data){
                        if( data.success ) {
                            console.log(data);
                            $('#errorsText').hide();

                            retrieveOrders(type);
                            alert('Order updated successfully');
                        } else {
                            $('#errorsText').text(data.message).show();
                        }
                    },
                    error: function(e){
                        console.log(e);
                        alert("<|Server communication error|/>!");
                    }
                });
            }

            function undoOrderUpdate(orderId) {
                let amount = $('#order-'+orderId+' .oldAmount').val();

                $('#order-'+orderId+' .amount').html(amount);

                $('#order-'+orderId+' .updateOrder').removeClass('d-none');
                $('#order-'+orderId+' .deleteOrder').removeClass('d-none');
                $('#order-'+orderId+' .saveOrderUpdate').addClass('d-none');
                $('#order-'+orderId+' .undoOrderUpdate').addClass('d-none');
            }

            function deleteOrder(orderId) {
                if (confirm('Are you sure to delete your order of code '+$('#order-'+orderId+' td:first-child').text()+' ?')) {
                    $.ajax({
                        type: "POST",
                        url: "Orders/deleteOrder",
                        data: {
                            'order_id': orderId
                        },
                        success: function(data){
                            if( data.success ) {
                                console.log(data);
                                $('#errorsText').hide();

                                retrieveOrders(type);
                                alert('Order deleted successfully');
                            } else {
                                $('#errorsText').text(data.message).show();
                            }
                        },
                        error: function(e){
                            console.log(e);
                            alert("Server communication error!");
                        }
                    });
                }
            }

            function checkAmount() {
                // amount > 0 e solo numeri
                // no 'e', ',', '.', '-'
            }
        </script>
    </body>
</html>