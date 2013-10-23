function lineGraph(yy1,yy2,wicket1,wicket2,div_name,first_team_name,second_team_name) {
    var x = [],count=[];
    for(var i=0;i<20;i++) {
        x[i]=i+1; 
        count[i]=0;
    }               
    var r = Raphael(div_name, 800, 500);

    var options = {
        //gutter: 10,
        symbol: "",
        colors: [
        "#995555",       // the first line is red 
        "#555599",       // the second line is blue
        "transparent"    // the third line is invisible
        ],
        nostroke: false,
        smooth: false,
        shade: false,
        dash: "",
        axis: [false,false,1,1],
        axisxstep: 19,
        axisystep: 9,
        wickets: [wicket1,wicket2,[]],
        angles:[30,150],
        team_names:[first_team_name,second_team_name]
    };
    
    function fin_wicket() {
                this.tag=r.g.tag(this.x, this.y, this.message, this.angle, 10).insertBefore(this);
    }
    function fout_wicket() {                
            this.tag.remove();                        
    }
    var lines=r.g.linechart(10, 10, 750, 400,x,[yy1, yy2,[0,20]],options).hover(fin_wicket,fout_wicket);
}