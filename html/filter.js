function myFunction(table, first, second) {
  // Declare variables
  var input, filter, table, tr, td, i;
  input = document.getElementsByClassName("search_".concat(table));
  filter = input[0].value.toUpperCase();
  table = document.getElementsByClassName("results_".concat(table));
  tr = table[0].getElementsByTagName("tr");

  console.log("Searching", filter);

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td_shortcut = tr[i].getElementsByTagName("td")[first];
    td_name = tr[i].getElementsByTagName("td")[second]
    if (td_shortcut || td_name) {
      if ((td_shortcut.innerHTML.toUpperCase().indexOf(filter) > -1)||(td_name.innerHTML.toUpperCase().indexOf(filter) > -1)){
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}