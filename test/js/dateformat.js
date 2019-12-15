$(function(){
    var inputs = $(".hhm-dateInputer");
    var dateStr = "____-__-__";
    inputs.each(function(index,elem){
        var input = $(this);
        input.on("keydown",function(event){
            var that = this;   //当前触发事件的输入框。
            var key = event.keyCode;
            var cursorIndex = getCursor(that);
            //输入数字
            if(key >= 48 && key <= 57){
                //光标在日期末尾或光标的下一个字符是"-",返回false,阻止字符显示。
                if(cursorIndex == dateStr.length || that.value.charAt(cursorIndex) === "-") {return false;}
                //字符串中无下划线时，返回false
                if(that.value.search(/_/) === -1){return false;}
                var fron = that.value.substring(0,cursorIndex); //光标之前的文本
                var reg = /(\d)_/;
                setTimeout(function(){ //setTimeout后字符已经输入到文本中
                    //光标之后的文本
                    var end = that.value.substring(cursorIndex,that.value.length);
                    //去掉新插入数字后面的下划线_
                    that.value = fron + end.replace(reg,"$1");
                    //寻找合适的位置插入光标。
                    resetCursor(that);
                },1);
                return true;
                //"Backspace" 删除键
            }else if( key == 8){
                //光标在最前面时不能删除。  但是考虑全部文本被选中下的删除情况
                if(!cursorIndex && !getSelection(that).length){ return false;}
                //删除时遇到中划线的处理
                if(that.value.charAt(cursorIndex -1 ) == "-"){
                    var ar = that.value.split("");
                    ar.splice(cursorIndex-2,1,"_");
                    that.value = ar.join("");
                    resetCursor(that);
                    return false;
                }
                setTimeout(function(){
                    //值为空时重置
                    if(that.value === "") {
                        that.value = "____-__-__";
                        resetCursor(that);
                    }
                    //删除的位置加上下划线
                    var cursor = getCursor(that);
                    var ar = that.value.split("");
                    ar.splice(cursor,0,"_");
                    that.value = ar.join("");
                    resetCursor(that);
                },1);
                return true;
            }
            return false;
        });
        input.on("focus",function(){
            if(!this.value){
                this.value = "____-__-__";
            }
            resetCursor(this);
        });
        input.on("blur",function(){
            if(this.value === "____-__-__"){
                this.value = "";
            }
        });
    });
    //设置光标到正确的位置
    function resetCursor(elem){
        var value = elem.value;
        var index = value.length;
        //当用户通过选中部分文字并删除时，此时只能将内容置为初始格式洛。
        if(elem.value.length !== dateStr.length){
            elem.value = dateStr;
        }
        var temp = value.search(/_/);
        index =  temp> -1? temp: index;
        setCursor(elem,index);
        //把光标放到第一个_下划线的前面
        //没找到下划线就放到末尾
    }
});
function getCursor(elem){
    //IE 9 ，10，其他浏览器
    if(elem.selectionStart !== undefined){
        return elem.selectionStart;
    } else{ //IE 6,7,8
        var range = document.selection.createRange();
        range.moveStart("character",-elem.value.length);
        var len = range.text.length;
        return len;
    }
}
function setCursor(elem,index){
    //IE 9 ，10，其他浏览器
    if(elem.selectionStart !== undefined){
        elem.selectionStart = index;
        elem.selectionEnd = index;
    } else{//IE 6,7,8
        var range = elem.createTextRange();
        range.moveStart("character",-elem.value.length); //左边界移动到起点
        range.move("character",index); //光标放到index位置
        range.select();
    }
}
function getSelection(elem){
    //IE 9 ，10，其他浏览器
    if(elem.selectionStart !== undefined){
        return elem.value.substring(elem.selectionStart,elem.selectionEnd);
    } else{ //IE 6,7,8
        var range = document.selection.createRange();
        return range.text;
    }
}
function setSelection(elem,leftIndex,rightIndex){
    if(elem.selectionStart !== undefined){ //IE 9 ，10，其他浏览器
        elem.selectionStart = leftIndex;
        elem.selectionEnd = rightIndex;
    } else{//IE 6,7,8
        var range = elem.createTextRange();
        range.move("character",-elem.value.length);  //光标移到0位置。
        //这里一定是先moveEnd再moveStart
        //因为如果设置了左边界大于了右边界，那么浏览器会自动让右边界等于左边界。
        range.moveEnd("character",rightIndex);
        range.moveStart("character",leftIndex);
        range.select();
    }
}