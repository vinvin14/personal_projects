function filterSearch() {
  var input, filter, table, tr, td, i;
  input = document.getElementById("search-emp");
  filter = input.value.toUpperCase();
  table = document.getElementById("emp_table");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    td2 = tr[i].getElementsByTagName("td")[1];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
function staffingUtil(){
    $("span[id='letter-opt']").on("click", function(){
        var a = $(this).text();
        $("span.letter-active").removeClass("letter-active");
        $(this).addClass("letter-active");
        $.ajax({
            url: "personnel/control-data.php",
            method: "POST",
            data:{
                letters:a
            },
            success: function(data){
                $("#show-staffing-result").html(data);
                $("li[id='item-icon']").on("click", function(){
                    //add highlight
                    $("li.active-li").removeClass("active-li");
                    $(this).addClass("active-li");
                    var pos = $(this).data('id');
                    $.ajax({
                         url: "personnel/control-data.php",
                         method: "POST",
                         data:{
                             position:pos
                         },
                        success: function(data){
                            $("#show-staffing-item").html(data);
                        }
                    });
                });
            }
        });
    });
    $('#addRow').on('click', function(){
        var row = $('<div class="col-md-12 mt-3 new-line"><input type="text" name="participants[]" class="white-field border-bottom" placeholder="Lastname, Firstname M.I."><i class="fa fa-times text-danger pt-1"></i> <span id="remove-line" class="span-remove">Remove this line</span></div>');
        $('.participants').append(row);
        $('span[id="remove-line"]').on('click', function(){
            $(this).parent().remove();
        });
    });
   
}