var day = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
var month = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'); 

var TimeNow = "";
function MakeTime(timesetter){
        timesetter.setTime(timesetter.getTime());
	timesetter.setTime(timesetter.getTime()+1000);
	var mthN = String(timesetter.getMonth());
	var mth = month[mthN];
	var mthday = String(timesetter.getDate());
	var yrN = String(timesetter.getFullYear());
	var dayN = String(timesetter.getDay());
	var dd = day[dayN];
	var hhN  = timesetter.getHours();
        if(hhN > 12){
	   var hh = String(hhN - 12);
	   var AP = "pm";
	}else if(hhN == 12){
	   var hh = "12";
	   var AP = "pm"; 
	}else if(hhN == 0){
	   var hh = "12";
	   var AP = "am";     
	}else{
	   var hh = String(hhN);
	   var AP = "am";
	}
    var mm  = String(timesetter.getMinutes());
    var ss  = String(timesetter.getSeconds());
    //TimeNow = "" + dd + " " +((hh < 10) ? " " : "") + hh + ((mm < 10) ? ":0" : ":") + mm + ((ss < 10) ? ":0" : ":") + ss + " " + AP + "  " + mth + " " + mthday + ", " + yrN + "";
    TimeNow = " "+((hhN < 10) ? "0" : "") + hhN + ((mm < 10) ? ":0" : ":") + mm + ((ss < 10) ? ":0" : ":") + ss + " ";
    return TimeNow;
}// end function MakeTime

function ShowClock(timesetter)
{
    TimeNow=MakeTime(timesetter);
    setTimeout(function(){ShowClock(timesetter);},1000);
    $("#clock").html(TimeNow);
    return false;
}// end function ShowClock
