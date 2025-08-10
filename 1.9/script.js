document.addEventListener('DOMContentLoaded',function(){
function getRandomInt(min, max) {
    min = Math.ceil(min); // 确保min是整数
    max = Math.floor(max); // 确保max是整数
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
var bgnum = getRandomInt(1,2)
var body = document.getElementById("body");
body.style.backgroundImage = "url(" + bgnum + ".png)"

function getQueryParamValue(paramName) {
    var queryString = window.location.search;
    queryString = queryString.substring(1);

    var params = queryString.split('&').reduce(function (acc, pair) {
        var [key, value] = pair.split('=');
        acc[decodeURIComponent(key)] = decodeURIComponent(value);
        return acc;
    }, {});

    return params[paramName] || '/dav';
}
var pathValue = getQueryParamValue('path');
if(pathValue[0] === '/' && pathValue.length === 1){
    pathValue = '';
}
const dac = document.querySelectorAll('.js-ex');
const texts = [];
dac.forEach(function(dac) {  
    const text = dac.textContent.trim();
    texts.push(text);
});
var links = document.getElementsByClassName("list-file");
var ids = [];
for (var i = 0; i < links.length; i++) {
    ids.push(links[i].id);
}
var w = 0
for (var i = 0; i < links.length; i++) {
    if(ids[i][0] === '/'){
        links[i].href = '?path=' + pathValue + ids[i];
    }else{
        links[i].href = '/explorer' + pathValue + '/' + ids[i] + '.' + texts[w];
        w = w + 1;
    }
}
})