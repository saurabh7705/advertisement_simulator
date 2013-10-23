function barGraph(y,bowlers,no_of_wickets_in_over_bowler,wicket,div_name,powerplay_start) {
    var x = [], count=[];
    for(var i=0;i<20;i++)
    {
        x[i]=i+1;
        count[i]=0;
    }                        
    var r = Raphael(div_name, 1000, 500);
    var index=0;
    fin = function () {
        for(var j=0;j<chart.bars[0].length;j++){
            if(chart.bars[0][j].id==this.bar.id)
                index=j;
        }
        this.tag=r.g.tag(this.bar.x, this.bar.y-10, "Runs:"+y[index]+"\n Bowler: "+bowlers[index], 0, 10).insertBefore(this);
    }
    fout = function () {
        this.tag.remove();
    }
    var chart=r.g.barchart(10, 10, 700, 350,[y]).hover(fin, fout);
    axis=r.g.axis(28,350,650,null,null,19,0,x,"+",2);
    axis.text.attr({font:"12px Arial", fill:"#000000", "font-weight": "regular", "color": "#000000"});
    //powerplay overs in different color
    for (var i=(powerplay_start+4-1); i>=(powerplay_start-1); i--) {
        if(chart.bars[0][i] != undefined){
            var bar = chart.bars[0][i];
            bar.attr("fill", "#bf2f2f");
            bar.attr("stroke", "#bf2f2f");
        }
    }

    //creating cirles for wickets
    for(var j=0;j<wicket.length;j++)
    {
            var over=wicket[j];
            over = over-1;
            var bar = chart.bars[0][over];
            count[over]=count[over]+1;
            if(bar != undefined){
                //sets the circle at x,y,radius
                var circle = r.circle( bar.x, (bar.y+10)-(count[over]*20), 10 );
                // Sets the fill attribute of the circle to red (#f00)
                circle.attr("fill", "#f00");
                // Sets the stroke attribute of the circle to white
                circle.attr("stroke", "#fff");
            }
    }
    
}