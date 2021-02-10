function dashboard_requests()
{
    $.ajax(
        {
            url: '/api/requests',
            method: 'get',
            statusCode: {
                404: function (data)
                {
                    $('#request').html('<div class="py-2 text-center text-danger"><small>'+data.responseJSON+'</small></div>');
                },
                200: function (data)
                {
                    // console.log(data.length );
                    $('#request-container').html('' +
                        '<table class="table table-borderless table-hover">' +
                        '<thead class="">' +
                        '<th><small>Request Code</small></th>' +
                        '<th><small>Client</small></th>' +
                        '<th></th>' +
                        '</thead>' +
                        '<tbody id="request"></tbody>' +
                        '</table>');
                    data.forEach( function (val, index)
                    {

                        $('#request').append('' +
                            '<tr class="pl-3 bg-light shadow-sm">' +
                            '<td>' +
                            ''+val.requestCode+'' +
                            '</td>' +
                            '<td>'+val.lastname+', '+val.firstname+'</td>' +
                            '<td><small class="text-primary" style="cursor: pointer">View Request</small></td>' +
                            '</tr>');
                        // console.log(val);
                    });
                }
            }

        }
    );
}

function clients()
{
    $.ajax({
        url: '/api/clients',
        type: 'GET',
        statusCode: {
            404: function (data){
                $('#client-display').html('<div class="h5">'+data.responseJSON+'</div>');
            },
            200: function (data)
            {
                console.log(data);
                $('#client-display').html('' +
                    '<table class="table table-borderless">' +
                    '<thead>' +
                    '<th><small>Name</small></th>' +
                    '<th><small>Employment Status</small></th>' +
                    '<th><small>Membership</small></th>' +
                    '<th></th>' +
                    '</thead>' +
                    '<tbody id="client-body"></tbody>' +
                    '</table>');
                data.forEach(function (val){
                    $('#client-body').append('' +
                        '<tr class="p-2 bg-light shadow-sm border-bottom">' +
                        '<td>'+val.lastname+', '+val.firstname+' '+val.middlename[0]+'.</td>' +
                        '<td>'+val.employment_status+'</td>' +
                        '<td>'+val.membership+'</td>' +
                        '<td><a href="">View</a></td>' +
                        '</tr>');
                });
            }
        }
    });
}
function clientsWL()
{
    $.ajax({
        url: '/api/clients/withLoans',
        type: 'GET',
        statusCode: {
            404: function (data)
            {

            },
            200: function (data)
            {

            }
        }
    });
}
function getNotifications(role)
{

    $('#notificationParent').click(function (){
        $('#notificationChild').toggle();
    });
    var callout = function (){
        $.ajax({
            async: true,
            url: '/notification/'+ role,
            type: 'get',
            success: function (data)
            {
                // console.log(data.notifications);
                data.notifications.forEach(function (val){
                    console.log(val.details);
                    $('.notification-container').append('<div class="notification-details">'+val.details+'</div>');
                });
                $('#notification').text(data.count);

                if(data.count === 0)
                {
                    $('.notification-container').html('<div>data.notification.details</div>');
                }
                else
                {
                    // setTimeout(callout()
                    //     , 50000);
                }
            }
        })
    }
    callout();
}

//jquery action
$(document).ready(function(){
    $(function () {
        $('[data-toggle="popover"]').popover()
    })

    $("#search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#searchReference tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    //incoming actions
    var partNum = $("#partNumber");
    partNum.click(function (){
        $('#incomingContainer').show();
        partNum.on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#incomingContainer div").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        $("#incomingContainer div").click(function (){
            partNum.val($(this).text());
            $('#incomingContainer').hide();
        });
    });
    $('input').not(partNum).focusin(function () {
        $('#incomingContainer').hide();
    });
    // end of incoming actions

    //for outgoing quantity check
    $('#outQuantity').on('blur', function (){
        var consumable = $('#outgoingpartNumber').val();
        var quantity = $(this).val();

        $.ajax({
            async: true,
            url: '/ajax/consumable',
            type: 'get',
            data: {
                'id': consumable
            },
            success: function (data)
            {
                if(data !== '')
                {
                    var finalQuantity = parseInt(data.quantity) - parseInt(quantity);
                    if(finalQuantity < 0)
                    {
                        if(confirm('Warning! We cannot accommodate the whole request since '+data.description+' has insufficient quantity! Quantity of '+parseInt(0-finalQuantity)+' will not be accommodated would you like to proceed?'))
                        {
                            $('#outSubmit').show();
                        }
                        else
                        {
                            $('#outQuantity').val(0);
                        }
                    }
                    else if(finalQuantity > 0)
                    {
                        $('#outSubmit').show();
                    }
                    else
                    {
                        $('#outSubmit').hide();
                    }
                }
            }

        });
    });
    var outPartNum = $('#outgoingpartNumber');

    outPartNum.click(function (){
        $('#outgoingContainer').show();
        outPartNum.on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#outgoingContainer div").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        $("#outgoingContainer div").click(function (){
            outPartNum.val($(this).text());
            // outPartNum.attr('data-id', $(this).attr('data-id'));
            $('#outgoingContainer').hide();
        });
    })
    $('input').not(outPartNum).focusin(function () {
        $('#outgoingContainer').hide();
    });

});
