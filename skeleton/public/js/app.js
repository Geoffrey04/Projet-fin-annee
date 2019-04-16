/* var input_style = document.getElementById('search_style');
var input_influence = document.getElementById('search_influence');


function search_name() {


    var input_name = document.getElementById('search_name');
    var filter = input_name.value.toUpperCase();
   // var tr = document.getElementById('Mytr');
    var td = document.getElementsByClassName('js-search-nom');

    for(var i = 0; i < td.length; i++)
    {
        var a = td[i].getElementsByTagName("a")[0];
        var txtvalue = a.textContent || a.innerText;

        if(txtvalue.toUpperCase().indexOf(filter) > -1)
        {
            td[i].parentNode.style.display= "";
        }else
            {
                td[i].parentNode.style.display = "none";
            }
    }

} */



