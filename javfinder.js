
var CryptoJS = require("crypto-js");

var CryptoJSAesJson = {
    stringify: function (cipherParams) {
        var j = {ct: cipherParams.ciphertext.toString(CryptoJS.enc.Base64)};
        if (cipherParams.iv) j.iv = cipherParams.iv.toString();
        if (cipherParams.salt) j.s = cipherParams.salt.toString();
        return JSON.stringify(j);
    },
    parse: function (jsonStr) {
        var j = JSON.parse(jsonStr);
        var cipherParams = CryptoJS.lib.CipherParams.create({ciphertext: CryptoJS.enc.Base64.parse(j.ct)});
        if (j.iv) cipherParams.iv = CryptoJS.enc.Hex.parse(j.iv);
        if (j.s) cipherParams.salt = CryptoJS.enc.Hex.parse(j.s);
        return cipherParams;
    }
};

var CryptoJSAESdecrypt = function(e,t) {
    try {
        return JSON.parse(CryptoJS.AES.decrypt(e, t, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8));
    } catch(er) {
        return null;
    }
}

var args = process.argv;
var obj = {"ct":args[2], "iv":args[3], "s":args[4]};
var json = JSON.stringify(obj);
console.log(CryptoJSAESdecrypt(json, "up3x.com"));



