function nl2br(str, is_xhtml) {
    if (typeof str === "undefined" || str === null) return "";

    var breakTag =
        is_xhtml || typeof is_xhtml === "undefined" ? "<br />" : "<br>";
    return (str + "").replace(
        /([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,
        "$1" + breakTag + "$2"
    );
}

function oneSpace(input) {
    var clearValue = input.val().replace(/\s\s+/g, " ");
    input.val(clearValue);
}

// 1,500,000 1,000
function sepNumber(num) {
    return num
        .replaceAll(",", "")
        .toString()
        .replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
}

/*
	$('form').find('input, select').keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
*/
