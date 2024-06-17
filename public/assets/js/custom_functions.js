const nl2br = function (str, is_xhtml) {
  if (typeof str === "undefined" || str === null) return "";

  var breakTag =
    is_xhtml || typeof is_xhtml === "undefined" ? "<br />" : "<br>";
  return (str + "").replace(
    /([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,
    "$1" + breakTag + "$2"
  );
};

const oneSpace = function (input) {
  var clearValue = input.val().replace(/\s\s+/g, " ");
  input.val(clearValue);
};

// 1,500,000 1,000
const sepNumber = function (num) {
  return num
    .toString()
    .replaceAll(",", "")
    .replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
};

const preventEnterToSubmit = function (form) {
  $(form)
    .find("input, select")
    .keydown(function (event) {
      if (event.keyCode == 13) {
        event.preventDefault();
        return false;
      }
    });
};
