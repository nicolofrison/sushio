<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Login - Sushio</title>
        <meta name="description" content="Sushio login">
        <meta name="author" content="Nicolò Frison">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">

    </head>

    <body id="loginPage" class="text-center d-flex align-items-center justify-content-center py-5">
        <div class="container">
            <form class="form-user">
                <h1 class="h3 mb-3 font-weight-normal">Log in</h1>
                <div id="errorsText" class="alert alert-danger" style="display: none"></div>
                <div id="step1">
                    <label for="inputName" class="sr-only"><?php echo ucfirst(lang('Common.name'));?></label>
                    <input type="text" id="inputName" class="form-control" placeholder="<?php echo ucfirst(lang('Common.name'));?>*" required="" autofocus="">
                    <label for="inputSurname" class="sr-only"><?php echo ucfirst(lang('Common.surname'));?></label>
                    <input type="text" id="inputSurname" class="form-control" placeholder="<?php echo ucfirst(lang('Common.surname'));?>*" required="" autofocus="">
                    <label for="inputUsername" class="sr-only"><?php echo ucfirst(lang('Common.username'));?></label>
                    <input type="text" id="inputUsername" class="form-control" placeholder="<?php echo ucfirst(lang('Common.username'));?>*" required="" autofocus="">
                    <button id="joinGroup" class="btn btn-lg btn-primary btn-block" type="button"><?php echo lang('Login.alreadyHaveAGroup');?></button>
                    <button id="createGroup" class="btn btn-lg btn-primary btn-block" type="button"><?php echo lang('Login.createNewGroup');?></button>
                </div>
                <div id="step2" style="display: none">
                    <input id="action" type="hidden">
                    <label for="inputGroupName" class="sr-only"><?php echo ucfirst(lang('Common.groupName'));?></label>
                    <input type="text" id="inputGroupName" class="form-control" placeholder="<?php echo ucfirst(lang('Common.groupName'));?>*" required="" autofocus="">
                    <label for="inputGroupPassword" class="sr-only"><?php echo ucfirst(lang('Common.groupPassword'));?></label>
                    <input type="password" id="inputGroupPassword" class="form-control" placeholder="<?php echo ucfirst(lang('Common.groupPassword'));?>*" required="" autofocus="">
                    <button id="submit" class="btn btn-lg btn-primary btn-block" type="button"></button>
                    <button id="back" class="btn btn-danger btn-block" type="button"><?php echo ucfirst(lang('Common.back'));?></button>
                </div>
            </form>
        </div>
        <script type="application/javascript" src="js/jquery-3.5.1.min.js"></script>
        <script type="application/javascript" src="js/jquery.md5.js"></script>
        <script>
            $(document).ready(function() {
                $('#joinGroup').click(function() {
                    if( $('#inputName').val() !== '' && $('#inputSurname').val() !== '' && $('#inputUsername').val() !== '' ) {
                        $('#errorsText').hide();
                        $('#step1').hide();
                        $('#step2').show();
                        $('#action').val('joinGroup');
                        $('#submit').text('<?php echo lang('Login.joinGroup');?>');
                    } else {
                        $('#errorsText').text('<?php echo lang('Error.allFieldsAreNeeded'); ?>').show();
                    }
                });

                $('#createGroup').click(function() {
                    if( $('#inputName').val() !== '' && $('#inputSurname').val() !== '' && $('#inputUsername').val() !== '' ) {
                        $('#errorsText').hide();
                        $('#step1').hide();
                        $('#step2').show();
                        $('#action').val('createGroup');
                        $('#submit').text('<?php echo lang('Login.createNewGroup');?>');
                    } else {
                        $('#errorsText').text('<?php echo lang('Error.allFieldsAreNeeded');?>').show();
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

            function login() {
                var md5Password = $.md5($('#inputGroupPassword').val());
                $.ajax({
                    type: "POST",
                    url: "Home/login",
                    data: {
                        'action': $('#action').val(),
                        'name': $('#inputName').val(),
                        'surname': $('#inputSurname').val(),
                        'username': $('#inputUsername').val(),
                        'groupName': $('#inputGroupName').val(),
                        'groupPassword': md5Password
                    },
                    success: function(data){
                        if( data.success ) {
                            console.log(data);

                            window.location.href = 'Orders';
                        } else {
                            $('#step2').hide();
                            $('#step1').show();
                            $('#errorsText').text(data.message).show();
                        }
                    },
                    error: function(e){
                        console.log(e);
                        alert('<?php echo addslashes(lang('Error.server')); ?>');
                    }
                });
            }
        </script>
    </body>
</html>