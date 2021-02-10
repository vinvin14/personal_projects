function getWithTarget(uri, target, identifier)
{
    $.ajax(
        {
            async: true,
            url: uri,
            type: 'get',
            statusCode: {
                200: function (data)
                {
                    switch (identifier) {
                        case 'unit':
                            $(target).html('');
                            data.forEach(function (val){
                                // console.log(val.unit);
                                $(target).append('<div class="p-2">'+val.unit+'</div>');
                            });
                            break;
                        case 'fc':
                            $(target).html('');
                            data.forEach(function (val){
                                // console.log(val.unit);
                                $(target).append('<div class="p-2"><strong>'+val.type+'</strong> - '+ val.code +'</div>');
                            });
                            break;
                        case 'st':
                            $(target).html('');
                            data.forEach(function (val){
                                // console.log(val.unit);
                                $(target).append('<div class="p-2"><strong>'+val.systemType+'</strong></div>');
                            });
                            break;
                        case 'ts':
                            $(target).html('');
                            data.forEach(function (val){
                                // console.log(val.unit);
                                $(target).append('<div class="p-2"><strong>'+val.typeOfService+'</strong></div>');
                            });
                            break;
                        case 'cus':
                            $(target).html('');
                            data.forEach(function (val){
                                // console.log(val.unit);
                                $(target).append('<div class="p-2"><strong>'+val.name+'</strong></div>');
                            });
                            break;
                        case 'loc':
                            $(target).html('');
                            data.forEach(function (val){
                                // console.log(val.unit);
                                $(target).append('<div class="p-2"><strong>'+val.location+'</strong></div>');
                            });
                            break;
                        case 'pur':
                            $(target).html('');
                            data.forEach(function (val){
                                // console.log(val.unit);
                                $(target).append('<div class="p-2"><strong>'+val.purpose+'</strong></div>');
                            });
                            break;
                        case 'fse':
                            $(target).html('');
                            data.forEach(function (val){
                                // console.log(val.unit);
                                $(target).append('<div class="p-2"><strong>'+val.lastname+', '+val.firstname+' '+val.middlename[0]+'.</strong></div>');
                            });
                            break;
                        case 'bt':
                            $(target).html('');
                            data.forEach(function (val){
                                // console.log(val.unit);
                                $(target).append('<div class="p-2"><strong>'+val.boardType+'</strong></div>');
                            });
                            break;
                    }
                },
                401: function (data)
                {
                    // console.log(data);
                    Swal.fire({
                        icon: 'error',
                        title: 'Request Denied!',
                        text: data.responseJSON,
                    });
                    $(".swal2-container.in").css('background-color', 'black');
                },
                404: function (data)
                {
                    console.log(data);
                },
                501: function (data)
                {
                    console.log(data);
                }
            }
        }
    );
}
function post(uri, data)
{
    $.ajax(
        {
            async: true,
            url: uri,
            type:'POST',
            data: data,
            statusCode: {
                200: function (data)
                {
                    console.log(data);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data,
                    });
                    $(".swal2-container.in").css('background-color', 'rgba(255, 255, 255, 1)');
                    $('input, select, textarea').val('');
                },
                401: function (data)
                {
                    console.log(data);
                    Swal.fire({
                        icon: 'error',
                        title: 'Request Denied!',
                        text: data.responseJSON,
                    });
                    $(".swal2-container.in").css('background-color', 'black');
                },
                500: function (data)
                {
                    console.log(data);
                },
                404: function (data)
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Request Denied!',
                        text: data.responseJSON,
                    });
                    $(".swal2-container.in").css('background-color', 'black');
                },
                501: function (data)
                {
                    console.log(data);
                }
            }
        }
    );
}


function reference(module)
{
    switch (module) {
        case 'unit':
            $.ajax(
                {
                    async: true,
                    url: '/api/units',
                    type: 'get',
                    statusCode: {
                        200: function (data)
                        {
                            $('#reference-unit').html('');
                            data.forEach(function (val){
                                $('#reference-unit').append('' +
                                    '<tr>' +
                                    '<td>'+val.unit+'</td>' +
                                    '<td><textarea class="form-control" cols="1" rows="1" readonly>'+val.description+'</textarea></td>' +
                                    '<td>' +
                                    '<div class="float-right mr-3">' +
                                    '<a href="#" data-id="'+val.id+'" data-unit="'+val.unit+'" data-description="'+val.description+'" id="unit-view" class="" data-toggle="modal" data-target="#referenceView" data-backdrop="static" data-keyboard="false"><i class="fas fa-eye pr-5"></i></a>' +
                                    '<a href="#" data-id="'+val.id+'" id="unit-delete"><i class="fas fa-trash-alt text-danger"></i></a>' +
                                    '</div>' +
                                    '</td>' +
                                    '</tr>');
                            });
                            //update
                            $('a[id="unit-view"]').click(function (){
                                var id = $(this).attr('data-id');
                                var unit = $(this).attr('data-unit');
                                var description = $(this).attr('data-description');
                                $('#col-update-target1-content').html('' +
                                    '<div class="p-2">' +
                                    '<label class="font-weight-bold">Unit</label>' +
                                    '<input class="form-control" id="unit-update" value="'+ unit +'">' +
                                    '</div>' +
                                    '<div class="p-2">' +
                                    '<label class="font-weight-bold">Description</label>' +
                                    '<textarea class="form-control" rows="10" id="unitDescription-update">'+ description +'</textarea>');
                                getWithTarget('api/units', '#col-update-target2-content', 'unit');
                                $('#referenceUpdateButton').click(function () {
                                    if(dataRequired(['#unit-update']) === 0)
                                    {
                                        var data = {
                                            'unit' : $('#unit-update').val(),
                                            'description' : $('#unitDescription-update').val(),
                                        };
                                        post('api/unit/'+id, data);
                                        $('input, select').css('border-color', '#cccccc');
                                        getWithTarget('api/units', '#col-target2-content', 'unit');
                                        // reference('unit');
                                    }

                                });
                            });
                            $('a[id="unit-delete"]').click(function (){
                                var id = $(this).attr('data-id');
                                if(confirm('Are you sure you want to delete?'))
                                {
                                    $.ajax(
                                        {
                                            async: true,
                                            url: 'api/unit/delete/'+id,
                                            type: 'get',
                                            statusCode: {
                                                200: function (data)
                                                {
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Success!',
                                                        text: data,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'rgba(255, 255, 255, 1)');
                                                    reference('unit');
                                                },
                                                401: function (data)
                                                {
                                                    // console.log(data);
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Request Denied!',
                                                        text: data.responseJSON,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'black');
                                                },
                                                500: function (data)
                                                {
                                                    console.log(data);
                                                },
                                                404: function (data)
                                                {
                                                    console.log(data);
                                                },
                                                501: function (data)
                                                {
                                                    Swal.fire({
                                                        icon: 'warning',
                                                        title: 'warning!',
                                                        text: data.responseJSON,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'rgba(255, 255, 255, 1)');
                                                }
                                            }
                                        }
                                    );
                                }
                            });
                        },
                        401: function (data)
                        {
                            // console.log(data);
                            Swal.fire({
                                icon: 'error',
                                title: 'Request Denied!',
                                text: data.responseJSON,
                            });
                            $(".swal2-container.in").css('background-color', 'black');
                        },
                        500: function (data)
                        {
                            console.log(data);
                        },
                        404: function (data)
                        {
                            $('#reference-unit').html('');
                            $('#reference-unit').html('' +
                                '<tr>' +
                                '<td colspan="2" class="text-center">No Record(s) Found!</td>' +
                                '</tr>');
                        },
                        501: function (data)
                        {
                            console.log(data);
                        }
                    }
                }
            );
            break;
        case 'fc':
            $.ajax(
                {
                    async: true,
                    url: '/api/faultcodes',
                    type: 'get',
                    statusCode: {
                        200: function (data)
                        {
                            console.log(data);
                            $('#reference-fc').html('');
                            data.forEach(function (val){
                                // console.log(val.id);
                                $('#reference-fc').append('' +
                                    '<tr>' +
                                    '<td>'+val.code+'</td>' +
                                    '<td>'+val.type+'</td>' +
                                    '<td><textarea class="form-control" cols="1" rows="1" readonly>'+val.description+'</textarea></td>' +
                                    '<td>' +
                                    '<div class="float-right mr-3">' +
                                    '<a href="#" data-id="'+val.id+'" data-code="'+val.code+'" data-type="'+val.type+'" data-description="'+val.description+'" id="fc-view" class="" data-toggle="modal" data-target="#referenceView" data-backdrop="static" data-keyboard="false"><i class="fas fa-eye pr-5"></i></a>' +
                                    '<a href="#" data-id="'+val.id+'" id="fc-delete"><i class="fas fa-trash-alt text-danger"></i></a>' +
                                    '</div>' +
                                    '</td>' +
                                    '</tr>');
                            });
                            //update
                            $('a[id="fc-view"]').click(function (){
                                var id = $(this).attr('data-id');
                                var code = $(this).attr('data-code');
                                var type = $(this).attr('data-type');
                                var description = $(this).attr('data-description');
                                $('#col-update-target1-content').html('' +
                                    '<div class="p-2">' +
                                    '<label class="font-weight-bold">Code</label>' +
                                    '<input class="form-control" id="code-update" value="'+ code +'">' +
                                    '</div>' +
                                    '<div class="p-2">' +
                                    '<label class="font-weight-bold">Type</label>' +
                                    '<input class="form-control" id="type-update" value="'+ type +'">' +
                                    '</div>' +
                                    '<div class="p-2">' +
                                    '<label class="font-weight-bold">Description</label>' +
                                    '<textarea class="form-control" rows="10" id="fcDescription-update">'+ description +'</textarea>');
                                getWithTarget('/api/faultcodes', '#col-update-target2-content', 'fc');
                                $('#referenceUpdateButton').click(function () {
                                    if(dataRequired(['#code-update', '#type-update']) === 0)
                                    {
                                        var data = {
                                            'code' : $('#code-update').val(),
                                            'type' : $('#type-update').val(),
                                            'description' : $('#fcDescription-update').val(),
                                        };
                                        post('/api/faultcode/'+id, data);
                                        $('input, select').css('border-color', '#cccccc');
                                        getWithTarget('/api/faultcodes', '#col-target2-content', 'fc');
                                        // reference('unit');
                                    }
                                });
                            });
                            //delete
                            $('a[id="fc-delete"]').click(function (){
                                var id = $(this).attr('data-id');
                                if(confirm('Are you sure you want to delete?'))
                                {
                                    $.ajax(
                                        {
                                            async: true,
                                            url: '/api/faultcode/delete/'+id,
                                            type: 'get',
                                            statusCode: {
                                                200: function (data)
                                                {
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Success!',
                                                        text: data,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'rgba(255, 255, 255, 1)');
                                                    reference('fc');
                                                },
                                                401: function (data)
                                                {
                                                    // console.log(data);
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Request Denied!',
                                                        text: data.responseJSON,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'black');
                                                },
                                                500: function (data)
                                                {
                                                    console.log(data);
                                                },
                                                404: function (data)
                                                {
                                                    console.log(data);
                                                },
                                                501: function (data)
                                                {
                                                    Swal.fire({
                                                        icon: 'warning',
                                                        title: 'warning!',
                                                        text: data.responseJSON,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'rgba(255, 255, 255, 1)');
                                                }
                                            }
                                        }
                                    );
                                }
                            });
                        },
                        401: function (data)
                        {
                            // console.log(data);
                            Swal.fire({
                                icon: 'error',
                                title: 'Request Denied!',
                                text: data.responseJSON,
                            });
                            $(".swal2-container.in").css('background-color', 'black');
                        },
                        404: function (data)
                        {
                            $('#reference-fc').html('');
                            $('#reference-fc').html('' +
                                '<tr>' +
                                '<td colspan="3" class="text-center">No Record(s) Found!</td>' +
                                '</tr>');
                        },
                    }
                }
            );
            break;
        case 'st':
            $.ajax(
                {
                    async: true,
                    url: '/api/systemtypes',
                    type: 'get',
                    statusCode: {
                        200: function (data)
                        {
                            console.log(data);
                            $('#reference-st').html('');
                            data.forEach(function (val){
                                // console.log(val.id);
                                $('#reference-st').append('' +
                                    '<tr>' +
                                    '<td>'+val.systemType+'</td>' +
                                    '<td><textarea class="form-control" cols="1" rows="1" readonly>'+val.description+'</textarea></td>' +
                                    '<td>' +
                                    '<div class="float-right mr-3">' +
                                    '<a href="#" data-id="'+val.id+'" data-systemType="'+val.systemType+'" data-description="'+val.description+'" id="st-view" class="" data-toggle="modal" data-target="#referenceView" data-backdrop="static" data-keyboard="false"><i class="fas fa-eye pr-5"></i></a>' +
                                    '<a href="#" data-id="'+val.id+'" id="st-delete"><i class="fas fa-trash-alt text-danger"></i></a>' +
                                    '</div>' +
                                    '</td>' +
                                    '</tr>');
                            });
                            //update
                            $('a[id="st-view"]').click(function (){
                                var id = $(this).attr('data-id');
                                var systemType = $(this).attr('data-systemType');
                                var description = $(this).attr('data-description');
                                $('#col-update-target1-content').html('' +
                                    '<div class="p-2">' +
                                    '<label class="font-weight-bold">System Type</label>' +
                                    '<input class="form-control" id="systemType-update" value="'+ systemType +'">' +
                                    '</div>' +
                                    '<div class="p-2">' +
                                    '<label class="font-weight-bold">Description</label>' +
                                    '<textarea class="form-control" rows="10" id="stDescription-update">'+ description +'</textarea>');
                                getWithTarget('/api/systemtypes', '#col-update-target2-content', 'st');
                                $('#referenceUpdateButton').click(function () {
                                    if(dataRequired(['#systemType-update']) === 0)
                                    {
                                        var data = {
                                            'systemType' : $('#systemType-update').val(),
                                            'description' : $('#stDescription-update').val(),
                                        };
                                        post('/api/systemtype/'+id, data);
                                        $('input, select').css('border-color', '#cccccc');
                                        getWithTarget('/api/systemtypes', '#col-target2-content', 'st');
                                        // reference('unit');
                                    }
                                });
                            });
                            //delete
                            $('a[id="st-delete"]').click(function (){
                                var id = $(this).attr('data-id');
                                if(confirm('Are you sure you want to delete?'))
                                {
                                    $.ajax(
                                        {
                                            async: true,
                                            url: '/api/systemtype/delete/'+id,
                                            type: 'get',
                                            statusCode: {
                                                200: function (data)
                                                {
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Success!',
                                                        text: data,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'rgba(255, 255, 255, 1)');
                                                    reference('st');
                                                },
                                                401: function (data)
                                                {
                                                    // console.log(data);
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Request Denied!',
                                                        text: data.responseJSON,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'black');
                                                },
                                                500: function (data)
                                                {
                                                    console.log(data);
                                                },
                                                404: function (data)
                                                {
                                                    console.log(data);
                                                },
                                                501: function (data)
                                                {
                                                    Swal.fire({
                                                        icon: 'warning',
                                                        title: 'warning!',
                                                        text: data.responseJSON,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'rgba(255, 255, 255, 1)');
                                                }
                                            }
                                        }
                                    );
                                }
                            });
                        },
                        401: function (data)
                        {
                            // console.log(data);
                            Swal.fire({
                                icon: 'error',
                                title: 'Request Denied!',
                                text: data.responseJSON,
                            });
                            $(".swal2-container.in").css('background-color', 'black');
                        },
                        404: function (data)
                        {
                            $('#reference-st').html('');
                            $('#reference-st').html('' +
                                '<tr>' +
                                '<td colspan="3" class="text-center">No Record(s) Found!</td>' +
                                '</tr>');
                        },
                    }
                }
            );
            break;
        case 'bt':
            $.ajax(
                {
                    async: true,
                    url: '/api/boardtypes',
                    type: 'get',
                    statusCode: {
                        200: function (data)
                        {
                            // console.log(data);
                            $('#reference-bt').html('');
                            data.forEach(function (val){
                                // console.log(val.id);
                                $('#reference-bt').append('' +
                                    '<tr>' +
                                    '<td>'+val.boardType+'</td>' +
                                    '<td><textarea class="form-control" cols="1" rows="1" readonly>'+val.description+'</textarea></td>' +
                                    '<td>' +
                                    '<div class="float-right mr-3">' +
                                    '<a href="#" data-id="'+val.id+'" data-boardType="'+val.boardType+'" data-description="'+val.description+'" id="bt-view" class="" data-toggle="modal" data-target="#referenceView" data-backdrop="static" data-keyboard="false"><i class="fas fa-eye pr-5"></i></a>' +
                                    '<a href="#" data-id="'+val.id+'" id="bt-delete"><i class="fas fa-trash-alt text-danger"></i></a>' +
                                    '</div>' +
                                    '</td>' +
                                    '</tr>');
                            });
                            //update
                            $('a[id="bt-view"]').click(function (){
                                var id = $(this).attr('data-id');
                                var boardType = $(this).attr('data-boardType');
                                var description = $(this).attr('data-description');
                                $('#col-update-target1-content').html('' +
                                    '<div class="p-2">' +
                                    '<label class="font-weight-bold">Board Type</label>' +
                                    '<input class="form-control" id="boardType-update" value="'+ boardType +'">' +
                                    '</div>' +
                                    '<div class="p-2">' +
                                    '<label class="font-weight-bold">Description</label>' +
                                    '<textarea class="form-control" rows="10" id="btDescription-update">'+ description +'</textarea>');
                                getWithTarget('/api/boardtypes', '#col-update-target2-content', 'bt');
                                $('#referenceUpdateButton').click(function () {
                                    if(dataRequired(['#boardType-update']) === 0)
                                    {
                                        var data = {
                                            'boardType' : $('#boardType-update').val(),
                                            'description' : $('#btDescription-update').val(),
                                        };
                                        post('/api/boardtype/'+id, data);
                                        $('input, select').css('border-color', '#cccccc');
                                        getWithTarget('/api/boardtypes', '#col-target2-content', 'bt');
                                        // reference('unit');
                                    }
                                });
                            });
                            //delete
                            $('a[id="bt-delete"]').click(function (){
                                var id = $(this).attr('data-id');
                                if(confirm('Are you sure you want to delete?'))
                                {
                                    $.ajax(
                                        {
                                            async: true,
                                            url: '/api/boardtype/delete/'+id,
                                            type: 'get',
                                            statusCode: {
                                                200: function (data)
                                                {
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Success!',
                                                        text: data,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'rgba(255, 255, 255, 1)');
                                                    reference('bt');
                                                },
                                                401: function (data)
                                                {
                                                    // console.log(data);
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Request Denied!',
                                                        text: data.responseJSON,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'black');
                                                },
                                                500: function (data)
                                                {
                                                    console.log(data);
                                                },
                                                404: function (data)
                                                {
                                                    console.log(data);
                                                },
                                                501: function (data)
                                                {
                                                    Swal.fire({
                                                        icon: 'warning',
                                                        title: 'warning!',
                                                        text: data.responseJSON,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'rgba(255, 255, 255, 1)');
                                                }
                                            }
                                        }
                                    );
                                }
                            });
                        },
                        401: function (data)
                        {
                            // console.log(data);
                            Swal.fire({
                                icon: 'error',
                                title: 'Request Denied!',
                                text: data.responseJSON,
                            });
                            $(".swal2-container.in").css('background-color', 'black');
                        },
                        404: function (data)
                        {
                            // console.log(data);
                            $('#reference-bt').html('');
                            $('#reference-bt').html('' +
                                '<tr>' +
                                '<td colspan="3" class="text-center">No Record(s) Found!</td>' +
                                '</tr>');
                        },
                    }
                }
            );
            break;
        case 'ts':
            $.ajax(
                {
                    async: true,
                    url: '/api/typeofservices',
                    type: 'get',
                    statusCode: {
                        200: function (data)
                        {
                            console.log(data);
                            $('#reference-ts').html('');
                            data.forEach(function (val){
                                // console.log(val.id);
                                $('#reference-ts').append('' +
                                    '<tr>' +
                                    '<td>'+val.typeOfService+'</td>' +
                                    '<td><textarea class="form-control" cols="1" rows="1" readonly>'+val.description+'</textarea></td>' +
                                    '<td>' +
                                    '<div class="float-right mr-3">' +
                                    '<a href="#" data-id="'+val.id+'" data-typeOfService="'+val.typeOfService+'" data-description="'+val.description+'" id="ts-view" class="" data-toggle="modal" data-target="#referenceView" data-backdrop="static" data-keyboard="false"><i class="fas fa-eye pr-5"></i></a>' +
                                    '<a href="#" data-id="'+val.id+'" id="ts-delete"><i class="fas fa-trash-alt text-danger"></i></a>' +
                                    '</div>' +
                                    '</td>' +
                                    '</tr>');
                            });
                            //update
                            $('a[id="ts-view"]').click(function (){
                                var id = $(this).attr('data-id');
                                var typeOfService = $(this).attr('data-typeOfService');
                                var description = $(this).attr('data-description');
                                $('#col-update-target1-content').html('' +
                                    '<div class="p-2">' +
                                    '<label class="font-weight-bold">Type of Service</label>' +
                                    '<input class="form-control" id="typeOfService-update" value="'+ typeOfService +'">' +
                                    '</div>' +
                                    '<div class="p-2">' +
                                    '<label class="font-weight-bold">Description</label>' +
                                    '<textarea class="form-control" rows="10" id="tsDescription-update">'+ description +'</textarea>');
                                getWithTarget('/api/typeofservices', '#col-update-target2-content', 'ts');
                                $('#referenceUpdateButton').click(function () {
                                    if(dataRequired(['#typeOfService-update']) === 0)
                                    {
                                        var data = {
                                            'typeOfService' : $('#typeOfService-update').val(),
                                            'description' : $('#tsDescription-update').val(),
                                        };
                                        post('/api/typeofservice/'+id, data);
                                        $('input, select').css('border-color', '#cccccc');
                                        getWithTarget('/api/typeofservices', '#col-target2-content', 'ts');
                                        // reference('unit');
                                    }
                                });
                            });
                            //delete
                            $('a[id="ts-delete"]').click(function (){
                                var id = $(this).attr('data-id');
                                if(confirm('Are you sure you want to delete?'))
                                {
                                    $.ajax(
                                        {
                                            async: true,
                                            url: '/api/typeofservice/delete/'+id,
                                            type: 'get',
                                            statusCode: {
                                                200: function (data)
                                                {
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Success!',
                                                        text: data,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'rgba(255, 255, 255, 1)');
                                                    reference('ts');
                                                },
                                                401: function (data)
                                                {
                                                    // console.log(data);
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Request Denied!',
                                                        text: data.responseJSON,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'black');
                                                },
                                                500: function (data)
                                                {
                                                    console.log(data);
                                                },
                                                404: function (data)
                                                {
                                                    console.log(data);
                                                },
                                                501: function (data)
                                                {
                                                    Swal.fire({
                                                        icon: 'warning',
                                                        title: 'warning!',
                                                        text: data.responseJSON,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'rgba(255, 255, 255, 1)');
                                                }
                                            }
                                        }
                                    );
                                }
                            });
                        },
                        401: function (data)
                        {
                            // console.log(data);
                            Swal.fire({
                                icon: 'error',
                                title: 'Request Denied!',
                                text: data.responseJSON,
                            });
                            $(".swal2-container.in").css('background-color', 'black');
                        },
                        404: function (data)
                        {
                            $('#reference-st').html('');
                            $('#reference-st').html('' +
                                '<tr>' +
                                '<td colspan="3" class="text-center">No Record(s) Found!</td>' +
                                '</tr>');
                        },
                    }
                }
            );
            break;
        case 'cus':
            $.ajax(
                {
                    async: true,
                    url: '/api/customers',
                    type: 'get',
                    statusCode: {
                        200: function (data)
                        {
                            console.log(data);
                            $('#reference-customer').html('');
                            data.forEach(function (val){
                                // console.log(val.id);
                                $('#reference-customer').append('' +
                                    '<tr>' +
                                    '<td>'+val.customerID+'</td>' +
                                    '<td>'+val.name+'</td>' +
                                    '<td><textarea class="form-control" cols="1" rows="1" readonly>'+val.address+'</textarea></td>' +
                                    '<td>' +
                                    '<div class="float-right mr-3">' +
                                    '<a href="#" data-id="'+val.id+'" data-customerID="'+val.customerID+'" data-name="'+val.name+'" data-address="'+val.address+'" id="cus-view" class="" data-toggle="modal" data-target="#referenceView" data-backdrop="static" data-keyboard="false"><i class="fas fa-eye pr-5"></i></a>' +
                                    '<a href="#" data-id="'+val.id+'" id="cus-delete"><i class="fas fa-trash-alt text-danger"></i></a>' +
                                    '</div>' +
                                    '</td>' +
                                    '</tr>');
                            });
                            //update
                            $('a[id="cus-view"]').click(function (){
                                var id = $(this).attr('data-id');
                                var customerID = $(this).attr('data-customerID');
                                var name = $(this).attr('data-name');
                                var address = $(this).attr('data-address');
                                $('#col-update-target1-content').html('' +
                                    '<div class="p-2">' +
                                    '<label class="font-weight-bold">Customer ID</label>' +
                                    '<input class="form-control" id="customerID-update" value="'+ customerID +'">' +
                                    '</div>' +
                                    '<div class="p-2">' +
                                    '<label class="font-weight-bold">Customer</label>' +
                                    '<input class="form-control" id="customer-update" value="'+ name +'">' +
                                    '</div>' +
                                    '<div class="p-2">' +
                                    '<label class="font-weight-bold">Address</label>' +
                                    '<textarea class="form-control" rows="10" id="address-update">'+ address +'</textarea>');
                                getWithTarget('/api/customers', '#col-update-target2-content', 'cus');
                                $('#referenceUpdateButton').click(function () {
                                    if(dataRequired(['#customer-update']) === 0)
                                    {
                                        var data = {
                                            'customerID' : $('#customerID-update').val(),
                                            'name' : $('#customer-update').val(),
                                            'address' : $('#address-update').val(),
                                        };
                                        post('/api/customer/'+id, data);
                                        $('input, select').css('border-color', '#cccccc');
                                        getWithTarget('/api/customers', '#col-target2-content', 'cus');
                                        // reference('unit');
                                    }
                                });
                            });
                            //delete
                            $('a[id="cus-delete"]').click(function (){
                                var id = $(this).attr('data-id');
                                if(confirm('Are you sure you want to delete?'))
                                {
                                    $.ajax(
                                        {
                                            async: true,
                                            url: '/api/customer/delete/'+id,
                                            type: 'get',
                                            statusCode: {
                                                200: function (data)
                                                {
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Success!',
                                                        text: data,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'rgba(255, 255, 255, 1)');
                                                    reference('cus');
                                                },
                                                401: function (data)
                                                {
                                                    // console.log(data);
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Request Denied!',
                                                        text: data.responseJSON,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'black');
                                                },
                                                500: function (data)
                                                {
                                                    console.log(data);
                                                },
                                                404: function (data)
                                                {
                                                    console.log(data);
                                                },
                                                501: function (data)
                                                {
                                                    Swal.fire({
                                                        icon: 'warning',
                                                        title: 'warning!',
                                                        text: data.responseJSON,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'rgba(255, 255, 255, 1)');
                                                }
                                            }
                                        }
                                    );
                                }
                            });
                        },
                        401: function (data)
                        {
                            // console.log(data);
                            Swal.fire({
                                icon: 'error',
                                title: 'Request Denied!',
                                text: data.responseJSON,
                            });
                            $(".swal2-container.in").css('background-color', 'black');
                        },
                        404: function (data)
                        {
                            $('#reference-st').html('');
                            $('#reference-st').html('' +
                                '<tr>' +
                                '<td colspan="3" class="text-center">No Record(s) Found!</td>' +
                                '</tr>');
                        },
                    }
                }
            );
            break;
        case 'loc':
            $.ajax(
                {
                    async: true,
                    url: '/api/locations',
                    type: 'get',
                    statusCode: {
                        200: function (data)
                        {
                            console.log(data);
                            $('#reference-location').html('');
                            data.forEach(function (val){
                                // console.log(val.id);
                                $('#reference-location').append('' +
                                    '<tr>' +
                                    '<td>'+val.location+'</td>' +
                                    '<td><textarea class="form-control" cols="1" rows="1" readonly>'+val.description+'</textarea></td>' +
                                    '<td>' +
                                    '<div class="float-right mr-3">' +
                                    '<a href="#" data-id="'+val.id+'" data-location="'+val.location+'" data-description="'+val.description+'" id="loc-view" class="" data-toggle="modal" data-target="#referenceView" data-backdrop="static" data-keyboard="false"><i class="fas fa-eye pr-5"></i></a>' +
                                    '<a href="#" data-id="'+val.id+'" id="loc-delete"><i class="fas fa-trash-alt text-danger"></i></a>' +
                                    '</div>' +
                                    '</td>' +
                                    '</tr>');
                            });
                            //update
                            $('a[id="loc-view"]').click(function (){
                                var id = $(this).attr('data-id');
                                var location = $(this).attr('data-location');
                                var description = $(this).attr('data-description');
                                $('#col-update-target1-content').html('' +
                                    '<div class="p-2">' +
                                    '<label class="font-weight-bold">Location</label>' +
                                    '<input class="form-control" id="location-update" value="'+ location +'">' +
                                    '</div>' +
                                    '<div class="p-2">' +
                                    '<label class="font-weight-bold">Description</label>' +
                                    '<textarea class="form-control" rows="10" id="description-update">'+ description +'</textarea>');
                                getWithTarget('/api/locations', '#col-update-target2-content', 'loc');
                                $('#referenceUpdateButton').click(function () {
                                    if(dataRequired(['#location-update']) === 0)
                                    {
                                        var data = {
                                            'location' : $('#location-update').val(),
                                            'description' : $('#description-update').val(),
                                        };
                                        post('/api/location/'+id, data);
                                        $('input, select').css('border-color', '#cccccc');
                                        getWithTarget('/api/locations', '#col-target2-content', 'loc');
                                        // reference('unit');
                                    }
                                });
                            });
                            //delete
                            $('a[id="loc-delete"]').click(function (){
                                var id = $(this).attr('data-id');
                                if(confirm('Are you sure you want to delete?'))
                                {
                                    $.ajax(
                                        {
                                            async: true,
                                            url: '/api/location/delete/'+id,
                                            type: 'get',
                                            statusCode: {
                                                200: function (data)
                                                {
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Success!',
                                                        text: data,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'rgba(255, 255, 255, 1)');
                                                    reference('loc');
                                                },
                                                401: function (data)
                                                {
                                                    // console.log(data);
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Request Denied!',
                                                        text: data.responseJSON,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'black');
                                                },
                                                500: function (data)
                                                {
                                                    console.log(data);
                                                },
                                                404: function (data)
                                                {
                                                    console.log(data);
                                                },
                                                501: function (data)
                                                {
                                                    Swal.fire({
                                                        icon: 'warning',
                                                        title: 'warning!',
                                                        text: data.responseJSON,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'rgba(255, 255, 255, 1)');
                                                }
                                            }
                                        }
                                    );
                                }
                            });
                        },
                        401: function (data)
                        {
                            // console.log(data);
                            Swal.fire({
                                icon: 'error',
                                title: 'Request Denied!',
                                text: data.responseJSON,
                            });
                            $(".swal2-container.in").css('background-color', 'black');
                        },
                        404: function (data)
                        {
                            $('#reference-st').html('');
                            $('#reference-st').html('' +
                                '<tr>' +
                                '<td colspan="3" class="text-center">No Record(s) Found!</td>' +
                                '</tr>');
                        },
                    }
                }
            );
            break;
        case 'pur':
            $.ajax(
                {
                    async: true,
                    url: '/api/purposes',
                    type: 'get',
                    statusCode: {
                        200: function (data)
                        {
                            console.log(data);
                            $('#reference-purpose').html('');
                            data.forEach(function (val){
                                // console.log(val.id);
                                $('#reference-purpose').append('' +
                                    '<tr>' +
                                    '<td>'+val.purpose+'</td>' +
                                    '<td><textarea class="form-control" cols="1" rows="1" readonly>'+val.description+'</textarea></td>' +
                                    '<td>' +
                                    '<div class="float-right mr-3">' +
                                    '<a href="#" data-id="'+val.id+'" data-purpose="'+val.purpose+'" data-description="'+val.description+'" id="pur-view" class="" data-toggle="modal" data-target="#referenceView" data-backdrop="static" data-keyboard="false"><i class="fas fa-eye pr-5"></i></a>' +
                                    '<a href="#" data-id="'+val.id+'" id="pur-delete"><i class="fas fa-trash-alt text-danger"></i></a>' +
                                    '</div>' +
                                    '</td>' +
                                    '</tr>');
                            });
                            //update
                            $('a[id="pur-view"]').click(function (){
                                var id = $(this).attr('data-id');
                                var purpose = $(this).attr('data-purpose');
                                var description = $(this).attr('data-description');
                                $('#col-update-target1-content').html('' +
                                    '<div class="p-2">' +
                                    '<label class="font-weight-bold">Purpose</label>' +
                                    '<input class="form-control" id="purpose-update" value="'+ purpose +'">' +
                                    '</div>' +
                                    '<div class="p-2">' +
                                    '<label class="font-weight-bold">Description</label>' +
                                    '<textarea class="form-control" rows="10" id="description-update">'+ description +'</textarea>');
                                getWithTarget('/api/purposes', '#col-update-target2-content', 'pur');
                                $('#referenceUpdateButton').click(function () {
                                    if(dataRequired(['#purpose-update']) === 0)
                                    {
                                        var data = {
                                            'purpose' : $('#purpose-update').val(),
                                            'description' : $('#description-update').val(),
                                        };
                                        post('/api/purpose/'+id, data);
                                        $('input, select').css('border-color', '#cccccc');
                                        getWithTarget('/api/purposes', '#col-target2-content', 'pur');
                                        // reference('unit');
                                    }
                                });
                            });
                            //delete
                            $('a[id="pur-delete"]').click(function (){
                                var id = $(this).attr('data-id');
                                if(confirm('Are you sure you want to delete?'))
                                {
                                    $.ajax(
                                        {
                                            async: true,
                                            url: '/api/purpose/delete/'+id,
                                            type: 'get',
                                            statusCode: {
                                                200: function (data)
                                                {
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Success!',
                                                        text: data,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'rgba(255, 255, 255, 1)');
                                                    reference('pur');
                                                },
                                                401: function (data)
                                                {
                                                    // console.log(data);
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Request Denied!',
                                                        text: data.responseJSON,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'black');
                                                },
                                                500: function (data)
                                                {
                                                    console.log(data);
                                                },
                                                404: function (data)
                                                {
                                                    console.log(data);
                                                },
                                                501: function (data)
                                                {
                                                    Swal.fire({
                                                        icon: 'warning',
                                                        title: 'warning!',
                                                        text: data.responseJSON,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'rgba(255, 255, 255, 1)');
                                                }
                                            }
                                        }
                                    );
                                }
                            });
                        },
                        401: function (data)
                        {
                            // console.log(data);
                            Swal.fire({
                                icon: 'error',
                                title: 'Request Denied!',
                                text: data.responseJSON,
                            });
                            $(".swal2-container.in").css('background-color', 'black');
                        },
                        404: function (data)
                        {
                            $('#reference-st').html('');
                            $('#reference-st').html('' +
                                '<tr>' +
                                '<td colspan="3" class="text-center">No Record(s) Found!</td>' +
                                '</tr>');
                        },
                    }
                }
            );
            break;
        case 'fse':
            $.ajax(
                {
                    async: true,
                    url: '/api/fse',
                    type: 'get',
                    statusCode: {
                        200: function (data)
                        {
                            // console.log(data);
                            $('#reference-fse').html('');
                            data.forEach(function (val){
                                if(val.position === null)
                                    val.position = 'No Data';
                                if(val.whereAbouts === null)
                                    val.whereAbouts = 'No Data';
                                $('#reference-fse').append('' +
                                    '<tr>' +
                                    '<td>'+val.lastname+'</td>' +
                                    '<td>'+val.firstname+'</td>' +
                                    '<td>'+val.middlename+'</td>' +
                                    '<td>'+val.position+'</td>' +
                                    '<td>'+val.whereAbouts+'</td>' +
                                    '<td>' +
                                    '<div class="float-right mr-3">' +
                                    '<a href="#" data-id="'+val.id+'" id="fse-view" class="" data-toggle="modal" data-target="#referenceView" data-backdrop="static" data-keyboard="false"><i class="fas fa-eye pr-5"></i></a>' +
                                    '<a href="#" data-id="'+val.id+'" id="fse-delete"><i class="fas fa-trash-alt text-danger"></i></a>' +
                                    '</div>' +
                                    '</td>' +
                                    '</tr>');
                            });
                            //update
                            $('a[id="fse-view"]').click(function (){
                                var id = $(this).attr('data-id');
                                $.ajax({
                                    url: '/api/fse/'+id,
                                    type: 'GET',
                                    success:function (data) {
                                        $('#col-update-target1-content').html('' +
                                            '<div class="p-2">' +
                                            '<label class="font-weight-bold">Last Name</label>' +
                                            '<input class="form-control" id="lastname-update" value="'+ data.lastname +'">' +
                                            '</div>' +
                                            '<div class="p-2">' +
                                            '<label class="font-weight-bold">First Name</label>' +
                                            '<input class="form-control" id="firstname-update" value="'+ data.firstname +'">' +
                                            '</div>' +
                                            '<div class="p-2">' +
                                            '<label class="font-weight-bold">Middle Name</label>' +
                                            '<input class="form-control" id="middlename-update" value="'+ data.middlename +'">' +
                                            '</div>' +
                                            '<div class="p-2">' +
                                            '<label class="font-weight-bold">Position</label>' +
                                            '<input class="form-control" id="position-update" value="'+ data.position +'">' +
                                            '</div>' +
                                            '<div class="p-2">' +
                                            '<label class="font-weight-bold">WhereAbouts</label>' +
                                            '<input class="form-control" id="whereAbouts-update" value="'+ data.whereAbouts +'">' +
                                            '</div>' +
                                            '<div class="p-2">' +
                                            '<label class="font-weight-bold">Start of Service</label>' +
                                            '<input type="date" class="form-control" id="startOfService-update" value="'+ data.startOfService +'">' +
                                            '</div>' +
                                            '<div class="p-2">' +
                                            '<label class="font-weight-bold">Status</label>' +
                                            '<select class="form-control" id="status-update">' +
                                            '<option value="">-</option>' +
                                            '<option value="1">Active</option>' +
                                            '<option value="2">Inactive</option>' +
                                            '<option value="3">On Leave</option>' +
                                            '<option value="4">Suspended</option>' +
                                            '<option value="5">Resigned</option>' +
                                            '<option value="6">Retired</option>' +
                                            '<option value="7">Terminated</option>' +
                                            '</select>' +
                                            '</div>');
                                        $('#status').val(data.officerStatus);
                                        getWithTarget('/api/fse', '#col-update-target2-content', 'fse');
                                        $('#referenceUpdateButton').click(function () {
                                            if(dataRequired(['#purpose-update']) === 0)
                                            {
                                                var data = {
                                                    'firstname' : $('#firstname-update').val(),
                                                    'middlename' : $('#middlename-update').val(),
                                                    'lastname' : $('#lastname-update').val(),
                                                    'position' : $('#position-update').val(),
                                                    'whereAbouts' : $('#whereAbouts-update').val(),
                                                    'startOfService' : $('#startOfService-update').val(),
                                                    'officerStatus' : $('#status-update').val(),
                                                };
                                                post('/api/fse/update/'+id, data);
                                                $('input, select').css('border-color', '#cccccc');
                                                getWithTarget('/api/fse', '#col-target2-content', 'pur');
                                                // reference('unit');
                                            }
                                        });
                                    }
                                });
                            });
                            //delete
                            $('a[id="fse-delete"]').click(function (){
                                var id = $(this).attr('data-id');
                                if(confirm('Are you sure you want to delete?'))
                                {
                                    $.ajax(
                                        {
                                            async: true,
                                            url: '/api/fse/delete/'+id,
                                            type: 'get',
                                            statusCode: {
                                                200: function (data)
                                                {
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Success!',
                                                        text: data,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'rgba(255, 255, 255, 1)');
                                                    reference('fse');
                                                },
                                                401: function (data)
                                                {
                                                    // console.log(data);
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Request Denied!',
                                                        text: data.responseJSON,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'black');
                                                },
                                                500: function (data)
                                                {
                                                    console.log(data);
                                                },
                                                404: function (data)
                                                {
                                                    console.log(data);
                                                },
                                                501: function (data)
                                                {
                                                    Swal.fire({
                                                        icon: 'warning',
                                                        title: 'warning!',
                                                        text: data.responseJSON,
                                                    });
                                                    $(".swal2-container.in").css('background-color', 'rgba(255, 255, 255, 1)');
                                                }
                                            }
                                        }
                                    );
                                }
                            });
                        },
                        401: function (data)
                        {
                            // console.log(data);
                            Swal.fire({
                                icon: 'error',
                                title: 'Request Denied!',
                                text: data.responseJSON,
                            });
                            $(".swal2-container.in").css('background-color', 'black');
                        },
                        404: function (data)
                        {
                            $('#reference-st').html('');
                            $('#reference-st').html('' +
                                '<tr>' +
                                '<td colspan="3" class="text-center">No Record(s) Found!</td>' +
                                '</tr>');
                        },
                    }
                }
            );
            break;

    }
}
