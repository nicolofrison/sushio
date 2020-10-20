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
        <div id="successMessage" class="fixed-top alert alert-success" style="display: none"></div>
        <div class="container">
            <h1 class="py-3"><?php echo ucfirst(lang('Common.orders')); ?></h1>
            <div class="list-types d-flex justify-content-around align-items-stretch my-3">
                <button id="listType1" onclick="changeListType(1)" class="col-3 btn btn-sm btn-secondary" type="button"><?php echo lang('Orders.ownOrders'); ?></button>
                <button id="listType2" onclick="changeListType(2)" class="col-3 btn btn-sm btn-primary" type="button"><?php echo lang('Orders.allOrders'); ?></button>
                <button id="listType3" onclick="changeListType(3)" class="col-3 btn btn-sm btn-primary" type="button"><?php echo lang('Orders.allGroupedOrders'); ?></button>
            </div>
            <table id="ordersTable" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col"><?php echo ucfirst(lang('Common.code')); ?></th>
                    <th scope="col"><?php echo ucfirst(lang('Common.amount')); ?></th>
                    <th scope="col"><?php echo ucfirst(lang('Common.users')); ?></th>
                    <th scope="col"><?php echo ucfirst(lang('Common.actions')); ?></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
            <button id="completeOrders" class="btn btn-lg btn-primary" style="display: none" onclick="confirmOrders()" type="button"><?php echo lang('Orders.completeOrders'); ?></button>
        </div>
        <script type="application/javascript" src="js/jquery-3.5.1.min.js"></script>
        <script type="application/javascript" src="js/jquery.md5.js"></script>
        <script>
            $(document).ready(function() {
                retrieveOrders();
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
                    '<td></td>' +
                    '<td><input type="text" id="inputCode" class="form-control" placeholder="<?php echo ucfirst(lang('Common.code')); ?>" required="" autofocus=""></td>' +
                    '<td><input type="number" id="inputAmount" class="form-control" placeholder="<?php echo ucfirst(lang('Common.amount')); ?>" required="" autofocus=""></td>' +
                    '<td></td>' +
                    '<td><button id="orderButton" onclick="createOrder()" class="btn btn-success btn-block" type="button"><?php echo ucfirst(lang('Common.add')); ?></button></td>' +
                    '</tr>';

                $.ajax({
                    type: "POST",
                    url: "Orders/getJson/"+type,
                    data: {},
                    success: function(data){
                        if( data.success ) {
                            console.log(data);

                            data.message.forEach(row => tableBody += '<tr id="order-'+row.order_id+'" class="orderRow">' +
                                (type === 1 ? '<td><input type="checkbox" class="inputCheck form-control" onclick="toggleCheck('+row.order_id+')" '+(row.checked=="1"?'checked':'')+' /></td>':'<td><td/>') +
                                '<td>'+row.code+'</td>' +
                                '<td class="amount">'+row.amount+'</td>' +
                                '<td>'+row.username+'</td>' +
                                '<td class="actions">' +
                                    (type !== 3 && row.confirmed == 0 && row.actions ?
                                        '<button class="btn btn-warning btn-block updateOrder" onclick="updateOrder('+row.order_id+')"><?php echo ucfirst(lang('Common.edit')); ?></button>' +
                                        '<button class="btn btn-danger btn-block deleteOrder" onclick="deleteOrder('+row.order_id+')"><?php echo ucfirst(lang('Common.delete')); ?></button>' +
                                        '<button class="btn btn-success btn-block saveOrderUpdate d-none" onclick="saveOrderUpdate('+row.order_id+')"><?php echo ucfirst(lang('Common.save')); ?></button>' +
                                        '<button class="btn btn-danger btn-block undoOrderUpdate d-none" onclick="undoOrderUpdate('+row.order_id+')"><?php echo ucfirst(lang('Common.undo')); ?></button>'
                                    : '') +
                                    (row.confirmed > 0 ? '<?php echo ucfirst(lang('Common.order')); ?> ' + row.confirmed : '') +
                                '</td>' +
                            '</tr>');

                            $('#ordersTable tbody').html(tableBody);

                            if (type === 3) {
                                $('#completeOrders').show();
                            } else {
                                $('#completeOrders').hide();
                            }
                        } else {
                            $('#step2').hide();
                            $('#step1').show();
                            alert(data.message);
                        }
                    },
                    error: function(e){
                        console.log(e);
                        alert('<?php echo addslashes(lang('Error.server')); ?>');
                    }
                });
            }

            function successAlert(message) {
                $('#successMessage').text(message).show();
                setTimeout(() => {
                    $('#successMessage').fadeOut(1000);
                }, 1000);
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

                            retrieveOrders(type);

                            successAlert('<?php echo addslashes(lang('Orders.success.creation')); ?>');
                            setTimeout(() => {
                                $('#inputCode').focus();
                            }, 100);
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function(e){
                        console.log(e);
                        alert('<?php echo addslashes(lang('Error.server')); ?>');
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
                    '<input class="inputEditAmount form-control" type="number" placeholder="<?php echo addslashes(ucfirst(lang('Common.amount'))); ?>" value="'+amount+'"/>');

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

                            retrieveOrders(type);

                            successAlert('<?php echo addslashes(lang('Orders.success.update')); ?>');
                        } else {
                            alert(data.message);
							window.location.reload();
                        }
                    },
                    error: function(e){
                        console.log(e);
                        alert('<?php echo addslashes(lang('Error.server')); ?>');
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

            function toggleCheck(orderId) {
                let check = $('#order-'+orderId+' .inputCheck').is(':checked');

                $.ajax({
                    type: "POST",
                    url: "Orders/toggleCheckOrder",
                    data: {
                        'order_id': orderId,
                        'check': check
                    },
                    success: function(data){
                        if( data.success ) {
                            console.log(data);
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function(e){
                        console.log(e);
                        alert('<?php echo addslashes(lang('Error.server')); ?>');
                    }
                });
            }

            function deleteOrder(orderId) {
                if (confirm('<?php echo addslashes(lang('Orders.deleteConfirm')); ?>'+$('#order-'+orderId+' td:first-child').text()+' ?')) {
                    $.ajax({
                        type: "POST",
                        url: "Orders/deleteOrder",
                        data: {
                            'order_id': orderId
                        },
                        success: function(data){
                            if( data.success ) {
                                console.log(data);

                                retrieveOrders(type);

                                successAlert('<?php echo addslashes(lang('Orders.success.delete')); ?>');
                            } else {
                                alert(data.message);
                            }
                        },
                        error: function(e){
                            console.log(e);
                            alert('<?php echo addslashes(lang('Error.server')); ?>');
                        }
                    });
                }
            }

            function confirmOrders() {
                if (confirm('<?php echo addslashes(lang('Orders.completeConfirm')); ?>')) {
                    $.ajax({
                        type: "POST",
                        url: "Orders/completeOrder",
                        data: {},
                        success: function (data) {
                            if (data.success) {
                                console.log(data);

                                retrieveOrders(type);

                                successAlert('<?php echo addslashes(lang('Orders.success.complete')); ?>');
                            } else {
                                alert(data.message);
                            }
                        },
                        error: function (e) {
                            console.log(e);
                            alert('<?php echo addslashes(lang('Error.server')); ?>');
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