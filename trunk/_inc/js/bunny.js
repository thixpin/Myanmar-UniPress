(function(){
    //  Disable MUA Web Converter to prevent duplicate converting. 
    var disableMUA = document.createElement("div");
    disableMUA.setAttribute("style", "position: absolute; top: -99px; font-family: MyanmarFont !important;"); 
    disableMUA.setAttribute("id", "disableMUA");
    document.body.appendChild(disableMUA);

    // Preloading embedded font
    disableMUA.innerHTML = "က္က";
})();

// start code from rabbit

// End code from rabbit

'use strict';

// Detecting the browser's unicode redering
function mmFontWidth(text,embedded){
    var e = document.createElement("div");
    var style = embedded ? "position: absolute; top: -99px; letter-spacing: normal !important; font-family: MyanmarFont !important;" : "position: absolute; top: -99px; letter-spacing: normal !important;"
    e.setAttribute("style", style);
    e.innerHTML = text ;
    document.body.appendChild(e);
    var ew = e.clientWidth;
    e.parentNode.removeChild(e);
    return ew;
}

function isZawgyiBrowser(){
    return (mmFontWidth("က္က",false) >= mmFontWidth("က",false) * 1.5 );
}
function isCanRender(){
    return (mmFontWidth("က္က",true) <= mmFontWidth("က",true) * 1.5 );
}
var zawgyiUser =  isZawgyiBrowser(),
    canRender  = true;

//  End of redering detecting 

/* If converter result is not correct we need to normallize the some error. 
*  eg. double (ု) error due to mac zawgyi keyboard.
*/
function uniNormalize(text){
    text =  text.replace( /(\u102F)(\u102F)/g, "$1");
    return text;
}

/* This method will check the user font and content font.
*  If the content font is not equal the user font, this will convert to user font
*/

function autoConvert(text){
    var textIsZawgyi = isZawgyiTex(text);
    if(textIsZawgyi && !zawgyiUser){
        text = Rabbit.zg2uni(text);
    } else if(!textIsZawgyi && zawgyiUser){
        text = Rabbit.uni2zg(text);
    }
    return text;
}

var zawgyiRegex = "\u1031\u103b" // e+medial ra
    // beginning e or medial ra
    + "|^\u1031|^\u103b"
    // independent vowel, dependent vowel, tone , medial ra wa ha (no ya
    // because of 103a+103b is valid in unicode) , digit ,
    // symbol + medial ra
    + "|[\u1022-\u1030\u1032-\u1039\u103b-\u103d\u1040-\u104f]\u103b"
    // end with asat
    + "|\u1039$"
    // medial ha + medial wa
    + "|\u103d\u103c"
    // medial ra + medial wa
    + "|\u103b\u103c"
    // consonant + asat + ya ra wa ha independent vowel e dot below
    // visarga asat medial ra digit symbol
    + "|[\u1000-\u1021]\u1039[\u101a\u101b\u101d\u101f\u1022-\u102a\u1031\u1037-\u1039\u103b\u1040-\u104f]"
    // II+I II ae
    + "|\u102e[\u102d\u103e\u1032]"
    // ae + I II
    + "|\u1032[\u102d\u102e]"
    // I II , II I, I I, II II
    //+ "|[\u102d\u102e][\u102d\u102e]"
    // U UU + U UU
    //+ "|[\u102f\u1030][\u102f\u1030]" [ FIXED!! It is not so valuable zawgyi pattern ]
    // tall aa short aa
    //+ "|[\u102b\u102c][\u102b\u102c]" [ FIXED!! It is not so valuable zawgyi pattern ]
    // shan digit + vowel
    + "|[\u1090-\u1099][\u102b-\u1030\u1032\u1037\u103c-\u103e]"
    // consonant + medial ya + dependent vowel tone asat
    + "|[\u1000-\u102a]\u103a[\u102c-\u102e\u1032-\u1036]"
    // independent vowel dependent vowel tone digit + e [ FIXED !!! - not include medial ]
    + "|[\u1023-\u1030\u1032-\u1039\u1040-\u104f]\u1031"
    // other shapes of medial ra + consonant not in Shan consonant
    + "|[\u107e-\u1084][\u1001\u1003\u1005-\u100f\u1012-\u1014\u1016-\u1018\u101f]"
    // u + asat
    + "|\u1025\u1039"
    // eain-dray
    + "|[\u1081\u1083]\u108f"
    // short na + stack characters
    + "|\u108f[\u1060-\u108d]"
    // I II ae dow bolow above + asat typing error
    + "|[\u102d-\u1030\u1032\u1036\u1037]\u1039"
    // aa + asat awww
    + "|\u102c\u1039"
    // ya + medial wa
    + "|\u101b\u103c"
    // non digit + zero + \u102d (i vowel) [FIXED!!! rules tested zero + i vowel in numeric usage]
    + "|[^\u1040-\u1049]\u1040\u102d"
    // e + zero + vowel
    + "|\u1031?\u1040[\u102b\u105a\u102e-\u1030\u1032\u1036-\u1038]"
    // e + seven + vowel
    + "|\u1031?\u1047[\u102c-\u1030\u1032\u1036-\u1038]"
    // cons + asat + cons + virama
    //+ "|[\u1000-\u1021]\u103A[\u1000-\u1021]\u1039" [ FIXED!!! REMOVED!!! conflict with Mon's Medial ]
    // U | UU | AI + (zawgyi) dot below
    + "|[\u102f\u1030\u1032]\u1094"
    // virama + (zawgyi) medial ra
    + "|\u1039[\u107E-\u1084]"

    // rules add by thixpin
    // space + e + consonant
    + "|[ $A-Za-z0-9]\u1031[\u1000-\u1021]"
    // consonant + Visarga 
    + "|[\u1000-\u1021]\u1038";

var ZawgyiReg = new RegExp(zawgyiRegex);

/* Myanmar text checking regular expression 
 *  is the part of Myanmar Font Tagger
 * http://userscripts-mirror.org/scripts/review/103745 
 */
var MyanmarReg = new RegExp("[\u1000-\u1021]");

function isMyanmarText(input) {
    return MyanmarReg.test(input) ? true : false;
}

/*
 * This method will check and search Zawgyi Pattern with input text and 
 * return true, if the text is Zawgyi encoding.
 * Parm = input text
 * return = boolean 
 *
 */
function isZawgyiTex(input) {
    input = input.trim();
    //console.log(input);
    var textSplittedByLine = input.split(/[\f\n\r\t\v\u00a0\u1680\u180e\u2000-\u200a\u2028\u2029\u202f\u205f\u3000\ufeff]/);
    for (var i = 0; i < textSplittedByLine.length; i++) {
        var textSplitted = textSplittedByLine[i].split(" ");
        for (var j = 0; j < textSplitted.length; j++) {
            //  console.log(textSplitted[j]);
            if (j != 0)
                textSplitted[j] = " " + textSplitted[j];
            if (ZawgyiReg.test(textSplitted[j]))
                return true;
        }
    }
    return false;
}


function shouldIgnoreElement(node) {
    if (node.nodeName == "INPUT" || node.nodeName == "SCRIPT" || node.nodeName == "TEXTAREA") {
        return true;
    } else if (node.isContentEditable == true) {
        return true;
    }
    return false;
}

/*
 * This part are from Myanmar Font Tagger scripts developed by Ko Thant Thet Khin Zaw
 * http://userscripts-mirror.org/scripts/review/103745
 */
function add_class(parent, className) {
    if (    parent.className === null || (   
            parent.classList.contains(className) === false && 
            parent.classList.contains('text_exposed_show') == false
    )){
        parent.classList.add(className);
    }
}
function convert_Tree(parent) {
    if (parent instanceof Node === false || parent instanceof SVGElement) {
        return;
    }
    if (parent.className != null && (parent.classList.contains('_c_o_nvert_') === true || parent.classList.contains('myan_mar_Font') === true)) {
        //console.log("converted return");
        return;
    }
    for (var i = 0; i < parent.childNodes.length; i++) {
        var child = parent.childNodes[i];
        if (child.nodeType != Node.TEXT_NODE && child.hasChildNodes()) {
            convert_Tree(child);
        } else if (child.nodeType == Node.TEXT_NODE) {
            
            var text = child.textContent.replace(/[\u200b\uFFFD]/g, "");
            var mmText = (text && isMyanmarText(text)) ? true : false;      
            if(mmText){
                add_class(parent,'myan_mar_text');
            }
            if( mmText && isZawgyiTex(text) && canRender) {
                
                child.textContent = Rabbit.zg2uni(text);
                add_class(parent,'_c_o_nvert_');
                if(zawgyiUser){
                    add_class(parent,'myan_mar_Font');
                } 

            } else if(mmText && !isZawgyiTex(text)) {

                if(!canRender){
                    child.textContent = Rabbit.uni2zg(text);
                    add_class(parent,'_c_o_nvert_');
                } else if(zawgyiUser) {
                    add_class(parent,'myan_mar_Font');
                } 

            } 
            
        }
    }
}

function findParent(element){
    var parentElement = element.parentNode;
    var end = false;
    while(end === false){
        if(parentElement.childNodes.length > 1) {
            if(parentElement.lastChild.nodeName == 'DIV'){
                end = true ;
            } else {
                parentElement = parentElement.parentNode; 
            }            
        } else {
            end = true;
        }
    }
    if(parentElement.nodeName == 'SPAN'){
        parentElement.style.display = 'block';
    } else if(parentElement.nodeName == 'A'){
        parentElement.style.display = 'inline-block';
    }
    return parentElement;
}


var runObserver = function() {
    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;
    var list = document.querySelector('body');

    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type == 'childList') {
                for (var i = 0; i < mutation.addedNodes.length; i++) {
                    var node = mutation.addedNodes[i];
                    if (node.nodeType == Node.TEXT_NODE) {
                        convert_Tree(node.parentNode);
                    } else {
                        convert_Tree(node);
                    }
                }
            } else if (mutation.type == 'characterData') {
                convert_Tree(mutation.target);
            }
        });
    });

    if (list) {
        observer.observe(list, {
            childList: true,
            attributes: false,
            characterData: true,
            subtree: true
        });
    }
}

function checkOS(){
    var userAgent = navigator.userAgent,
    platform = window.navigator.platform,
    macosPlatforms = ['Macintosh', 'MacIntel', 'MacPPC', 'Mac68K'],
    windowsPlatforms = ['Win32', 'Win64', 'Windows', 'WinCE'],
    iosPlatforms = ['iPhone', 'iPad', 'iPod'],
    os = null;

    if (macosPlatforms.indexOf(platform) !== -1) {
        os = 'Mac OS';
    } else if (iosPlatforms.indexOf(platform) !== -1) {
        os = 'iOS';
    } else if (windowsPlatforms.indexOf(platform) !== -1) {
        os = 'Windows';
    } else if (/Android/.test(userAgent)) {
        os = 'Android';
    } else if (!os && /Linux/.test(platform)) {
        os = 'Linux';
    }

    return os;

}

function startBunny(){
    // document.getElementById("disableMUA").style.fontFamily = 'Pyidaungsu, ' + document.getElementById("disableMUA").style.fontFamily 
    // console.log(document.getElementById("disableMUA").style.fontFamily);
    document.getElementById("disableMUA").style.display = 'none';

    canRender = (checkOS() == 'Android') ? true : isCanRender();

    var title = document.title;
    document.title = isMyanmarText(title)? autoConvert(title) : title;
    
    var list = document.querySelector('body');
    if (!list) {
        if (document.addEventListener) {
            document.addEventListener("DOMContentLoaded",function(){
                runObserver();
            }, false);
        }
    } else {
        convert_Tree(document.body);
        runObserver();
    }
}


function init(){

    var bunnyStarted = false;

    // waiting the font loading to start the bunny
    document.fonts.ready.then(function () {
        bunnyStarted = true;
        startBunny();
    });

    // if document.fonts is not working  bunny will start after time out
    setTimeout(function(){
        if(!bunnyStarted){
            bunnyStarted = true;
            startBunny();
        }
    },2500);

}

init();

