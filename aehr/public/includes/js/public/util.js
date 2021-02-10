function breakdown()
{
    $.ajax(
        {
            url: '/ajax/get',
            method: 'get',
            data: {
                transaction: 'cases/breakdown',
                province: $(this).text()
            },
            success: function (data) {
                $('#table-breakdown').html('');
                $('#title-breakdown').html('Cordillera Administrative Region (<span class="text-danger">'+ data.length+'</span> Total Cases)');

                if(Array.isArray(data))
                {
                    data.forEach(function(val, index)
                    {
                        $('#table-breakdown').
                        append('<tr>' +
                            '<td>'+val.province+'</td>' +
                            '<td>'+val.tagging+'</td>' +
                            '<td>'+val.age+' '+val.age_by+'</td>' +
                            '<td>'+val.sex+'</td>' +
                            '<td>'+val.exposure+'</td>' +
                            '<td>'+val.status+'</td>' +
                            '</tr>');
                    });
                }
                else
                {
                    $('#table-breakdown').html('<tr><td class="text-center" colspan="6">'+data+'</td></tr>')
                }
            }
        }
    );
}
function dashboard_util()
{
   
    $('td[id="confirmed-trigger"]').click(function ()
    {
        $('#display-barangay').html('');
        $.ajax(
            {
                url: '/ajax/get',
                method: 'get',
                data: {
                    transaction: 'cases/confirmedWithBarangay',
                    province:$(this).attr('data-id')
                },
                success: function (data) {
                    data.forEach(function (val, index) {
                        $('#prov_desc').html(val.prov_desc);
                        $('#display-barangay')
                            .append('<div class="border-1 px-2 py-2 h6">'+val.mun_desc+', '+val.brgy_desc+': '+val.confirmed+' Confirmed Case(s)</div>');
                    });
                    $("#casesModal").on('hide.bs.modal', function(){
                        $('#display-barangay').html('');
                        $('#prov_desc').html('');
                    });

                }
            }
        );
    });
    
    $('span[id="badge-prov"]').click(function ()
    {
        $('.badge-prov').removeClass('badge-selection-active');
        $(this).addClass('badge-selection-active');

        $.ajax(
            {
                url: '/ajax/get',
                method: 'get',
                data: {
                    transaction: 'facility/province',
                    province: $(this).text()
                },
                success: function (data) {
                    $('#table-bedtrack').html('');
                    if(Array.isArray(data))
                    {
                        data.forEach(function(val, index)
                        {
                            var icu_avail = (parseInt(val.icu_beds)-parseInt(val.icu_beds_occupied));
                            var iso_avail = (parseInt(val.iso_beds)-parseInt(val.iso_beds_occupied));
                            $('#table-bedtrack').
                            append('<tr>' +
                                '<td>'+val.facility_name+'</td>' +
                                '<td>'+val.icu_beds+'</td>' +
                                '<td>'+val.icu_beds_occupied+'</td>' +
                                '<td>'+icu_avail+'</td>' +
                                '<td>'+val.iso_beds+'</td>' +
                                '<td>'+val.iso_beds_occupied+'</td>' +
                                '<td>'+iso_avail+'</td>' +
                                '</tr>');
                        });
                    }
                    else
                    {
                        $('#table-bedtrack').html('<tr><td colspan="7">'+data+'</td></tr>')
                    }
                }
            }
        );
    });
    $('#ppe_province').on('change', function ()
    {
        $.ajax(
            {
                url: '/ajax/get',
                method: 'get',
                data: {
                    transaction: 'ppe/facility',
                    province: $(this).val()
                },
                success: function (data)
                {
                    $('#ppe_facilities').html(data);
                }
            }
        );
    });
    $('#ppe_facilities').on('change', function ()
    {
        $.ajax(
            {
                url: '/ajax/get',
                method: 'get',
                data: {
                    transaction: 'ppe/facility/result',
                    facility: $(this).val()
                },
                success: function (data)
                {
                    data.forEach(function (val, index)
                    {
                       $('#facility-display').html(val.facility_name);
                       switch (val.item)
                       {
                           case 'surgical-masks':
                                $('#ppe-mask').text(val.quantity);
                               break;
                           case 'gloves':
                               $('#ppe-gloves').text(val.quantity);
                               break;
                           case 'gowns':
                               $('#ppe-gowns').text(val.quantity);
                               break;
                           case 'shoe-cover':
                               $('#ppe-shoecover').text(val.quantity);
                               break;
                           case 'N95':
                               $('#ppe-n95').text(val.quantity);
                               break;
                           case 'goggles':
                               $('#ppe-goggles').text(val.quantity);
                               break;
                           case 'test-kits':
                               $('#ppe-testkits').text(val.quantity);
                               break;
                           case 'head-cap':
                               $('#ppe-headcap').text(val.quantity);
                               break;
                       }

                    });
                }
            }
        );
    });
    //breakdown

    $('span[id="badge-breakdown"]').click(function ()
    {
        var title = $(this).text();
        $('.badge-breakdown').removeClass('badge-breakdown-active');
        $(this).addClass('badge-breakdown-active');
        $('#title-breakdown').text(title);
        $.ajax(
            {
                url: '/ajax/get',
                method: 'get',
                data: {
                    transaction: 'cases/breakdown/province',
                    province: $(this).text()
                },
                success: function (data) {
                    $('#table-breakdown').html('');
                    $('#title-breakdown').append(' ('+data.length+' Total Cases)');
                    length = data.length;
                    if(Array.isArray(data))
                    {
                        data.forEach(function(val, index)
                        {
                            $('#table-breakdown').
                            append('<tr>' +
                                '<td>'+val.province+'</td>' +
                                '<td>'+val.tagging+'</td>' +
                                '<td>'+val.age+' '+val.age_by+'</td>' +
                                '<td>'+val.sex+'</td>' +
                                '<td>'+val.exposure+'</td>' +
                                '<td>'+val.status+'</td>' +
                                '</tr>');
                        });
                    }
                    else
                    {
                        $('#table-breakdown').html('');
                        $('#title-breakdown').text(title);
                        $('#table-breakdown').html('<tr><td class="text-center" colspan="6">'+data+'</td></tr>')
                    }
                }
            }
        );

    });
}//end
