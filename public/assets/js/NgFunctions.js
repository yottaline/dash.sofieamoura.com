class NgFunctions {
    static jsonParse = (str) => JSON.parse(str);
    static slice = (str, start, end) => str.slice(start, end);
    static sepNumber = (num) => sepNumber(num);
    static nl2br = (str) => nl2br(str);
    static toFixed = (num, decimal) => num.toFixed(decimal);
    static openUrl = (url, target = "_blank") => window.open(url, target);
    static findInArrOfObjs = (arr, key, val) => arr.find((o) => o[key] == val);
  }
