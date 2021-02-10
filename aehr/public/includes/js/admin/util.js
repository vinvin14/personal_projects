function showFacilityTab()
{
    $(document).ready(function () {
        $('.prov-selection').click(function () {
            prov = $(this).attr('data-id');

            $('.prov-selection').removeClass('prov-selection-active');
            $(this).addClass('prov-selection-active');

            $('#facility-display').html('<span id="facility-type">Hospital</span><span id="facility-type">RHU</span>');
            $('span[id="facility-type"]').click(function () {
                $.ajax({
                    async: true,
                    url: '/ajax/get',
                    method: 'get',
                    data: {
                        transaction: 'facilities',
                        province: prov,
                        type: $(this).text()
                    },
                    success:function(data){
                        $('#facility-display').html(data);
                    }
                });
            });

        });
    });
}
function showFacilityOption(){
    $('span[id="facility-type"]').click(function () {
        $.ajax({
            async: true,
            url: '/ajax/get',
            method: 'get',
            data: {
                transaction: 'facilities',
                province: prov,
                type: $(this).text()
            },
            success:function(data){
                $('#facility-display').html(data);
            }
        });
    });
}
function saveAggregates()
{
    $('input[id="admitted"]').on('change', function () {
        var id = $(this).attr('data-id');
        var value = $(this).val();

        $.ajax({
            async: true,
            url: '/ajax/post',
            method: 'post',
            data: {
                transaction: 'admitted',
                id: id,
                data: value
            },
            success: function (data) {
                if(data[0] != 200)
                {
                    alert('There was an error updating this entry!');
                }
            }
        });
    });
    $('input[id="recovered"]').on('change', function () {
        var id = $(this).attr('data-id');
        var value = $(this).val();

        $.ajax({
            async: true,
            url: '/ajax/post',
            method: 'post',
            data: {
                transaction: 'recovered',
                id: id,
                data: value
            },
            success: function (data) {
                // console.log(data);
            }
        });
    });
    $('input[id="died"]').on('change', function () {
        var id = $(this).attr('data-id');
        var value = $(this).val();

        $.ajax({
            async: true,
            url: '/ajax/post',
            method: 'post',
            data: {
                transaction: 'died',
                id: id,
                data: value
            },
            success: function (data) {
                // console.log(data);
            }
        });
    });
    $('input[id="mech_vents"]').on('change', function () {
        var id = $(this).attr('data-id');
        var value = $(this).val();

        $.ajax({
            async: true,
            url: '/ajax/post',
            method: 'post',
            data: {
                transaction: 'mech_vents',
                id: id,
                data: value
            },
            success: function (data) {
                // console.log(data);
            }
        });
    });
    $('input[id="icu_beds"]').on('change', function () {
        var id = $(this).attr('data-id');
        var value = $(this).val();

        $.ajax({
            async: true,
            url: '/ajax/post',
            method: 'post',
            data: {
                transaction: 'icu_beds',
                id: id,
                data: value
            },
            success: function (data) {
                // console.log(data);
            }
        });
    });
    $('input[id="icu_beds_occupied"]').on('change', function () {
        var id = $(this).attr('data-id');
        var value = $(this).val();

        $.ajax({
            async: true,
            url: '/ajax/post',
            method: 'post',
            data: {
                transaction: 'icu_beds_occupied',
                id: id,
                data: value
            },
            success: function (data) {
                // console.log(data);
            }
        });
    });
    $('input[id="iso_beds"]').on('change', function () {
        var id = $(this).attr('data-id');
        var value = $(this).val();

        $.ajax({
            async: true,
            url: '/ajax/post',
            method: 'post',
            data: {
                transaction: 'iso_beds',
                id: id,
                data: value
            },
            success: function (data) {
                // console.log(data);
            }
        });
    });
    $('input[id="iso_beds_occupied"]').on('change', function () {
        var id = $(this).attr('data-id');
        var value = $(this).val();

        $.ajax({
            async: true,
            url: '/ajax/post',
            method: 'post',
            data: {
                transaction: 'iso_beds_occupied',
                id: id,
                data: value
            },
            success: function (data) {
                // console.log(data);
            }
        });
    });
}
function showCasesMunicipalitiesOption()
{
    $('.prov-selection').click(function () {
        prov = $(this).attr('data-id');

        $('.prov-selection').removeClass('prov-selection-active');
        $(this).addClass('prov-selection-active');
            $.ajax({
                async: true,
                url: '/ajax/get',
                method: 'get',
                data: {
                    transaction: 'cases',
                    province: prov,
                },
                success:function(data){
                    $('#facility-display').html(data);
                }
            });
    });
}
function showCasesMunicipalitiesResult()
{
    $("span[class='cases-municipality']").click(function (){
        $('.cases-municipality').removeClass('cases-municipality-active');
        $(this).addClass('cases-municipality-active');

        var municipality = $(this).attr('id');
        $.ajax({
            async: true,
            url: '/ajax/get',
            method: 'get',
            data: {
                transaction: 'cases/result',
                municipality: municipality
            },
            success: function (data) {
                $('#cases-display').html(data);
            }
        });
    });
}
function saveCasesAggregates()
{
    $('button[id="cases-update"]').on('click', function () {
        var id = $(this).attr('data-id');
        var confirmed = $('#'+id+'confirmed').val();
        var suspected = $('#'+id+'suspected').val();
        var possible = $('#'+id+'possible').val();
        var probable = $('#'+id+'probable').val();

        // console.log('ID: '+id+' Confirmed: '+confirmed+ ' Suspected: '+ suspected + 'Possible: '+ possible +' Probable: '+ probable );

        $.ajax({
            async: true,
            url: '/ajax/post',
            method: 'post',
            data: {
                transaction: 'cases/update',
                id: id,
                confirmed: confirmed,
                suspected: suspected,
                possible: possible,
                probable: probable,
            },
            success: function (data) {
                // console.log(data);
                if(data[0] != 200)
                {
                    alert('There was an error updating this entry!');
                }
                else{
                    $('#'+id+'update-stat').html('<span class="text-success">✓</span>');
                    $('#'+id+'update-stat').fadeOut(5000)
                }

            }
        });
    });
}
function showFacilities()
{
    $('.prov-selection').click(function () {
        prov = $(this).attr('data-id');

        $('.prov-selection').removeClass('prov-selection-active');
        $(this).addClass('prov-selection-active');
        $.ajax({
            async: true,
            url: '/ajax/get',
            method: 'get',
            data: {
                transaction: 'ppe',
                province: prov,
            },
            success:function(data){
                $('#ppe-display').html(data);
            }
        });

    });
}
function showFacilityPPE()
{
    $("span[class='ppe-facility']").click(function (){
        $('.ppe-facility').removeClass('ppe-facility-active');
        $(this).addClass('ppe-facility-active');

        var facility = $(this).attr('id');
        $.ajax({
            async: true,
            url: '/ajax/get',
            method: 'get',
            data: {
                transaction: 'ppe/result',
                facility: facility
            },
            success: function (data) {
                // console.log(data);
                $('#ppe-result-display').html(data);
            }
        });
    });
}
function updatePPE()
{
    $('input[id="ppe_quantity"]').on('change', function () {
        var id = $(this).attr('data-id');
        var value = $(this).val();

        $.ajax({
            async: true,
            url: '/ajax/post',
            method: 'post',
            data: {
                transaction: 'ppe',
                id: id,
                data: value
            },
            success: function (data) {
                if(data[0] != 200)
                {
                    alert('There was an error updating this entry!');
                }
            }
        });
    });
    $('input[id="ppe_standard"]').on('change', function () {
        var id = $(this).attr('data-id');
        var value = $(this).val();

        $.ajax({
            async: true,
            url: '/ajax/post',
            method: 'post',
            data: {
                transaction: 'ppe_standard',
                id: id,
                data: value
            },
            success: function (data) {
                if(data[0] != 200)
                {
                    alert('There was an error updating this entry!');
                }
            }
        });
    });
    $('input[id="ppe_unit"]').on('change', function () {
        var id = $(this).attr('data-id');
        var value = $(this).val();

        $.ajax({
            async: true,
            url: '/ajax/post',
            method: 'post',
            data: {
                transaction: 'ppe_unit',
                id: id,
                data: value
            },
            success: function (data) {
                if(data[0] != 200)
                {
                    alert('There was an error updating this entry!');
                }
            }
        });
    });
}
function showFacilityOption()
{
    $('#province').on('change', function () {
        var province = $(this).find(':selected').text();

        $.ajax({
            async: true,
            url: '/ajax/get',
            method: 'get',
            data: {
                transaction: 'facilityByProvince',
                province: province
            },
            success: function (data) {
                $('#facility').html(data);
            }
            
        });
    });
}
