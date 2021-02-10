function search(input, reference)
{
    $(input).on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(reference).filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
}
function dataRequired(selectors)
{
    var count = [];
    selectors.forEach(function (item){
        $(item).siblings('#error').remove();
        if($(item).val() === '')
        {
            $(item).css('border','1px solid red');
            $(item).parent().append('<small id="error" class="text-danger">Error! Data Required</small>');
            count.push(1);
        }
        else
        {
            $(item).css('border','1px solid green');
            $(item).siblings('#error').remove();
        }
    });
    return count.length;
}

