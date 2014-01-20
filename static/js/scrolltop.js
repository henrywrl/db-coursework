// JavaScript Document
//From the Internet
function goTopEx(){
        
        var oobj=document.getElementById("goTopBtn");

        function getScrollTop(){
            return document.documentElement.scrollTop;
        }

        function setScrollTop(value){
            document.documentElement.scrollTop=value;
        }

        window.onscroll=function(){
            getScrollTop()>0?oobj.style.display="block":oobj.style.display="none";
        }
        
        oobj.onclick=function(){
            var goTop=setInterval(scrollMove,10);
            function scrollMove(){
                    setScrollTop(getScrollTop()/1.1);
                    if(getScrollTop()<1){
                        clearInterval(goTop);
                    }
                }
        }
    }