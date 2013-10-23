Backbone.Collection.prototype.collectionFind = function(comparator, value) {
	return _.filter(this.models, function(model){ return model.get(comparator) == value; })
}

Backbone.Collection.prototype.collectionFindFirst = function(comparator, value) { 
	return this.collectionFind(comparator, value)[0];
}

Backbone.View.prototype.showPlayerDetails = function(event) {
    return $(event.target).popover('show');
}

Backbone.View.prototype.hidePlayerDetails = function(event) {
    return $(event.target).popover('hide');
}
	
Innings = Backbone.Model.extend({
	defaults : {
		number : 1,
		batting_team_name : "",
		total_runs : 0,
		total_wickets : 0,
		total_balls : 0,
		started : false,
		home_team : false,
		currentlyBowlingPlayers : [],
		battingScoreCard : null,
		bowlingScoreCard : null,
		fallOfWickets : null,
		yetToBatView : null,
		fallOfWicketsView : null,
		battingScoreCardView : null,
		bowlingScoreCardView : null
	},
	initializeInnings : function() {
		var battingRecords = [];
		var bowlingRecords = [];
        var fallOfWickets = [];
		$.each(this.get("battingScoreCard"), function(index, player) {
			battingRecords.push(new BattingRecord(player));
		})
		
		$.each(this.get("bowlingScoreCard"), function(index, player) {
			bowlingRecords.push(new BowlingRecord(player));
		})
        
        $.each(this.get("fallOfWickets"), function(index, fall_of_wicket) {
			fallOfWickets.push(new BattingRecordFallOfWicket(fall_of_wicket));
		})

		this.set({ battingScoreCard : new BattingScoreCard(battingRecords) });
		this.set({ bowlingScoreCard : new BowlingScoreCard(bowlingRecords) });
        this.set({ fallOfWickets : new FallOfWickets(fallOfWickets) });

		this.set({ battingScoreCardView : new BattingScoreCardView({collection : this.get('battingScoreCard')}) });
		this.set({ bowlingScoreCardView : new BowlingScoreCardView({collection : this.get('bowlingScoreCard')}) });
		this.set({ yetToBatView : new BattingScoreCardYetToBatView({collection : this.get('battingScoreCard')}) });
		this.set({ fallOfWicketsView : new FallOfWicketsView({collection : this.get('fallOfWickets')}) });
	},
	handlePreInningsEvent : function(event) {
		this.get('battingScoreCard').collectionFindFirst('position', 1).setAsPlaying();
		this.get('battingScoreCard').collectionFindFirst('position', 2).setAsPlaying();
		this.set({started : true});
		miniBattingScoreCardView.collection = this.get('battingScoreCard');
		miniBattingScoreCardView.render();
		miniBowlingScoreCardView.collection = this.get('bowlingScoreCard');;
		miniBowlingScoreCardView.render();
	},
	handlePreOverEvent : function(event) {
		var currently_bowling_players = this.get("currentlyBowlingPlayers");
		last_bowler_id = currently_bowling_players[currently_bowling_players.length - 1];
		currently_bowling_players.push(event.bowler_id);
		$.each(this.get('bowlingScoreCard').models, function(index, bowler){
			bowler.setCurrentlyBowling(false);
		});			
		if(typeof last_bowler_id != 'undefined')
			this.get('bowlingScoreCard').collectionFindFirst('id', last_bowler_id).setCurrentlyBowling(true);
		this.get('bowlingScoreCard').collectionFindFirst('id', event.bowler_id).setCurrentlyBowling(true);
	},
	handleBallEvent : function(event) {
		var batsmanStats = this.get('battingScoreCard').collectionFindFirst('id', event.batsman_id);
		var bowlerStats = this.get('bowlingScoreCard').collectionFindFirst('id', event.bowler_id);
		this.set({total_runs : event.total, total_wickets : event.wickets, total_balls : this.get("total_balls") + 1});

		if(event.event_type == "out") {
			var fallOfWicket = new BattingRecordFallOfWicket({
				wicket_number : event.wickets,
				team_runs : event.total,
				over : event.over_ball,
				batsman_name : event.batsman_name	
			});
			this.get('fallOfWickets').add(fallOfWicket);
		}
		
		batsmanStats.handleBallEvent(event);
		bowlerStats.handleBallEvent(event);
	},
	handleNewBatsmanEvent : function(event) {
		this.get('battingScoreCard').collectionFindFirst('id', event.batsman_id).setAsPlaying();
	}
});

Match = Backbone.Collection.extend({
	model : Innings
});

InningsView = Backbone.View.extend({
	initialize : function() {
		_.bindAll(this, 'render');
		_.bindAll(this, 'renderContent');
		this.model.bind("change", this.render);
		this.model.bind("change:started", this.renderContent);
		this.template = _.template($("#innings_header").html());
	},
	render : function() {
		if(this.model.get("started")) {
            var renderedContent = this.template(this.model.toJSON());
			$(this.el).html(renderedContent);
            $('#mini_scorecard_header').html(renderedContent);
        }
		return this;
	},
	renderContent : function() {
		if(this.model.get('number') == 1)
			var innings_div_id = 'first_innings';
		else
			var innings_div_id = 'second_innings';
		if(this.model.get('started') == true) {
            var renderedContent = this.render().el;
			$('#'+innings_div_id+' #innings_header').html(renderedContent);
			$('#'+innings_div_id+' #innings_batting').html(this.model.get('battingScoreCardView').render().el)
			$('#'+innings_div_id+' #innings_yet_to_bat').html(this.model.get('yetToBatView').render().el)
			$('#'+innings_div_id+' #innings_fall_of_wickets').html(this.model.get('fallOfWicketsView').render().el)
			$('#'+innings_div_id+' #innings_bowling').html(this.model.get('bowlingScoreCardView').render().el)
		}
	}
});

MatchHeaderView = Backbone.View.extend({
	initialize : function() {
		_.bindAll(this, 'render');
		this.collection.bind("change", this.render);
		this.template = _.template($("#match_header").html());
	},
	render : function() {
		var home_team_innings = this.collection.collectionFindFirst('home_team', true);
		var away_team_innings = this.collection.collectionFindFirst('home_team', false);
		$(this.el).html(this.template({home_team_innings : home_team_innings, away_team_innings : away_team_innings}));
		return this;
	}
})

BattingRecord = Backbone.Model.extend({
	defaults : {
		id : 0,
		position : 0,
		name : "",
        full_name : "",
        last_name : "",
		mode_of_dismissal : 'not out',
		runs_scored : 0,
		balls_faced : 0,
		fours : 0,
		sixes : 0,
		striker : false,
		currently_batting : false,
		yet_to_bat : true,
		wicket_keeper:false,
		captain:false,
		pop_over_info:"",
        batting_style_name:"",
        bowling_style_name:"",
        bat_power: 0
	},
	setStriker : function(striker_value) {
		this.set({striker : striker_value});
		return this;
	},
	setAsPlaying: function() {
		this.set({yet_to_bat:false, currently_batting:true});
	},
	handleBallEvent : function(event) {
		var runs = parseInt(event.runs) - parseInt(event.byes);
		this.set({
			runs_scored : this.get('runs_scored') + runs,
			balls_faced : this.get('balls_faced') + 1,
			mode_of_dismissal : event.mode_of_dismissal,
			bat_power : parseInt(event.bat_power)
		});
		if(runs == 4)
			this.set({fours : this.get('fours') + 1});
		if(runs == 6)
			this.set({sixes : this.get('sixes') + 1});
		if(event.mode_of_dismissal && event.mode_of_dismissal != "not out")
			this.set({currently_batting : false, striker : false});
	}
});

BattingRecordFallOfWicket = Backbone.Model.extend({
	defaults : {
		wicket_number : 0,
		batsman_name : "",
		team_runs : 0,
		over : 0
	}
});

FallOfWickets = Backbone.Collection.extend({
	model : BattingRecordFallOfWicket
});

BattingScoreCard = Backbone.Collection.extend({
	model:BattingRecord
});

MiniBattingScoreCardView = Backbone.View.extend({
	tagName : 'table',
	attributes : {
		'class' : 'default_scorecard table'
	},
    events : {      
        "mouseenter .player_popover" : "showPlayerDetails",
        "mouseleave .player_popover" : "hidePlayerDetails"
    },
	initialize : function() {
		_.bindAll(this, 'render');
		this.collection.bind("change", this.render);
		this.template = _.template($("#mini_batting_scorecard").html());
	},
	render: function() {
		var battingScoreCardTable = this.template();
		var battingRecordViewContents = [];
		var currentlyPlayingModels = _.filter(this.collection.models, function(model){ return (model.get('yet_to_bat') == false && model.get('currently_batting') == true); })
		$.each(currentlyPlayingModels, function(index, battingRecord) {
			var battingRecordView = new BattingRecordView({model:battingRecord})
			battingRecordViewContents.push(battingRecordView.render().el);
		});
		if(battingRecordViewContents.length > 0) {
			var battingScoreCardTableBody = $('<tbody></tbody>');
			$.each(battingRecordViewContents, function(index, battingRecordViewContent) {
				battingScoreCardTableBody.append(battingRecordViewContent);
			});
			$(this.el).html(battingScoreCardTable).append(battingScoreCardTableBody.html());
		}
		return this;
	}
});

MiniBowlingScoreCardView = Backbone.View.extend({
	tagName : 'table',
	attributes : {
		'class' : 'default_scorecard table'
	},
    events : {      
        "mouseenter .player_popover" : "showPlayerDetails",
        "mouseleave .player_popover" : "hidePlayerDetails"
    },
	initialize : function() {
		_.bindAll(this, 'render');
		this.collection.bind("change", this.render);
		this.template = _.template($("#mini_bowling_scorecard").html());
	},
	render: function() {
		var bowlingScoreCardTable = this.template();
		var bowlingRecordViewContents = Array();
		var currentlyBowlingModels = this.collection.collectionFind('currently_bowling', true);
		$.each(currentlyBowlingModels, function(index, bowlingRecord) {
			var bowlingRecordView = new BowlingRecordView({model:bowlingRecord})
			bowlingRecordViewContents.push(bowlingRecordView.render().el);
		});
		if(bowlingRecordViewContents.length > 0) {
			var bowlingScoreCardTableBody = $('<tbody></tbody>');
			$.each(bowlingRecordViewContents, function(index, bowlingRecordViewContent) {
				bowlingScoreCardTableBody.append(bowlingRecordViewContent);
			});
			$(this.el).html(bowlingScoreCardTable).append(bowlingScoreCardTableBody.html());
		}
		return this;
	}
});

BattingScoreCardView = Backbone.View.extend({
	tagName : 'table',
	attributes : {
		'class' : 'm10 batting_card table',
		width : '600'
	},
    events : {      
        "mouseenter .player_popover" : "showPlayerDetails",
        "mouseleave .player_popover" : "hidePlayerDetails"
    },
	initialize : function() {
		_.bindAll(this, 'render');
		this.collection.bind("change", this.render);
		this.template = _.template($("#batting_scorecard").html());
	},
	render: function() {
		var battingScoreCardTable = this.template();
		var battingRecordViewContents = [];
		var playingOrAlreadyPlayedModels = this.collection.collectionFind('yet_to_bat', false);
		$.each(playingOrAlreadyPlayedModels, function(index, battingRecord) {
			var battingRecordView = new BattingRecordView({model:battingRecord})
			battingRecordViewContents.push(battingRecordView.render().el);
		});
		if(battingRecordViewContents.length > 0) {
			var battingScoreCardTableBody = $('<tbody></tbody>');
			$.each(battingRecordViewContents, function(index, battingRecordViewContent) {
				battingScoreCardTableBody.append(battingRecordViewContent);
			});
			$(this.el).html(battingScoreCardTable).append(battingScoreCardTableBody.html());
		}
		return this;
	}
});

BattingScoreCardYetToBatView = Backbone.View.extend({
    events : {      
        "mouseenter .player_popover" : "showPlayerDetails",
        "mouseleave .player_popover" : "hidePlayerDetails"
    },
	initialize : function() {
		_.bindAll(this, 'render');
		this.collection.bind("change:yet_to_bat", this.render);
		this.template = _.template($("#yet_to_bat").html());
	},
	render: function() {
		var yetToBatBattingRecordModels = this.collection.collectionFind('yet_to_bat', true);
        if(yetToBatBattingRecordModels.length > 0) {
          var yetToBatView = this.template({yet_to_bat_batsmen:yetToBatBattingRecordModels})
          $(this.el).html(yetToBatView);
        }
		return this;
	}
});


FallOfWicketsView = Backbone.View.extend({
    events : {      
        "mouseenter .player_popover" : "showPlayerDetails",
        "mouseleave .player_popover" : "hidePlayerDetails"
    },
	initialize : function() {
		_.bindAll(this, 'render');
		this.collection.bind("add", this.render);
		this.template = _.template($("#fall_of_wickets").html());
	},
	render: function() {
		if(this.collection.models.length > 0)
			$(this.el).html(this.template({fall_of_wickets_batsmen : this.collection.models}));		
		return this;
	}
});

BattingRecordView = Backbone.View.extend({
	tagName : 'tr',
	initialize : function() {
		_.bindAll(this, 'render');
		this.model.bind("change", this.render);
		this.template = _.template($("#batting_record").html());
	},
	
	render : function() {
		$(this.el).html(this.template(this.model.toJSON()));
		return this;
	}
});

BowlingRecord = Backbone.Model.extend({
	defaults : {
		id : 0,
		bowling_position: 100,
		name : "",
        full_name : "",
        last_name: "",
		balls_bowled : 0,
		runs_conceded : 0,
		wickets : 0,
		currently_bowling : false,
        pop_over_info : "",
        bowling_style_name:"",
        ball_power: 0
	},
	setCurrentlyBowling :  function(currentlyBowling) {
		this.set({currently_bowling : currentlyBowling});
		return this;
	},
	handleBallEvent : function(event) {
		var runs = parseInt(event.runs) - parseInt(event.byes);
		this.set({
			runs_conceded : this.get('runs_conceded') + runs,
			balls_bowled : this.get('balls_bowled') + 1,
			ball_power : parseInt(event.ball_power)
		});
		if(event.mode_of_dismissal && event.mode_of_dismissal != "not out")
			this.set({wickets : this.get('wickets') + 1});
	}
});

BowlingScoreCard = Backbone.Collection.extend({
	model:BowlingRecord,
	comparator: function(model) {
        return model.get('bowling_position');
    }
});

BowlingScoreCardView = Backbone.View.extend({
	tagName : 'table',
	attributes : {
		'class' : 'batting_card bowling_card table',
		width : '60%'
	},
    events : {      
        "mouseenter .player_popover" : "showPlayerDetails",
        "mouseleave .player_popover" : "hidePlayerDetails"
    },
	initialize : function() {
		_.bindAll(this, 'render');
		this.collection.bind("change", this.render);
		this.template = _.template($("#bowling_scorecard").html());
	},
	render: function() {
		var bowlingScoreCardTable = this.template();
		var bowlingRecordViewContents = Array();
		this.collection.each(function(bowlingRecord) {
			if(bowlingRecord.get("balls_bowled") > 0) {
				var bowlingRecordView = new BowlingRecordView({model:bowlingRecord})
				bowlingRecordViewContents.push(bowlingRecordView.render().el);
			}
		});
		if(bowlingRecordViewContents.length > 0) {
			var bowlingScoreCardTableBody = $('<tbody></tbody>');
			$.each(bowlingRecordViewContents, function(index, bowlingRecordViewContent) {
				bowlingScoreCardTableBody.append(bowlingRecordViewContent);
			});
			$(this.el).html(bowlingScoreCardTable).append(bowlingScoreCardTableBody.html());
		}
		return this;
	}
});

BowlingRecordView = Backbone.View.extend({
	tagName : 'tr',
	initialize : function() {
		_.bindAll(this, 'render');
		this.model.bind("change", this.render);
		this.template = _.template($("#bowling_record").html());
	},
	
	render : function() {
		$(this.el).html(this.template(this.model.toJSON()));
		return this;
	}
});

function initialize() {
    var first_innings_attributes_obj = eval('('+first_innings_attributes+')')
    var second_innings_attributes_obj = eval('('+second_innings_attributes+')')
	var homeTeamPlayingFirst = (isHomeTeamPlayingFirst == 1)
    firstInnings = new Innings(first_innings_attributes_obj);
    secondInnings = new Innings(second_innings_attributes_obj);
    firstInnings.set({batting_team_name : firstBattingTeamName, home_team : homeTeamPlayingFirst});
    secondInnings.set({batting_team_name : secondBattingTeamName, home_team : !homeTeamPlayingFirst});
    firstInnings.initializeInnings();
    secondInnings.initializeInnings();
	firstInningsView = new InningsView({model : firstInnings});
	secondInningsView = new InningsView({model : secondInnings});
	match = new Match([firstInnings, secondInnings]);
	matchHeaderView = new MatchHeaderView({collection : match});
	
    if(secondInnings.get("started"))
      var innings = secondInnings;
    else
      var innings = firstInnings;
	miniBattingScoreCardView = new MiniBattingScoreCardView({collection:innings.get('battingScoreCard')});
	miniBowlingScoreCardView = new MiniBowlingScoreCardView({collection:innings.get('bowlingScoreCard')});
    
	summaryCommentaryList = new CommentaryList;
	summaryCommentaryListView = new SummaryCommentaryListView({collection : summaryCommentaryList});
    
    matchClient.set({next_batsman_id : nextBatsmanId})
    updateNextBatsmanId(matchClient.get("next_batsman_id"));
}

function updateRecordsAndRenderScorecards(events) {
	$.each(events, function(index, event) {
		if(event.inning == 1)
			innings = firstInnings;
		else if(event.inning == 2)
			innings = secondInnings;
		
		if(event.event_type == "pre_innings")
			innings.handlePreInningsEvent(event);
		if(event.event_type == "pre_over")
			innings.handlePreOverEvent(event);
		if(event.event_type=="new_batsman")
			innings.handleNewBatsmanEvent(event);
		if(event.event_type=="runs" || event.event_type=="out") {
			if(event.event_type == 'out'){
				event.runs = 0;
				event.byes = 0;
			}
			innings.handleBallEvent(event);
		}
	});
	updateNextBatsmanId(matchClient.get("next_batsman_id"));
	updateTitle();
}

function updateNextBatsmanId(nextBatsmanId) {    
	if(nextBatsmanId){
		var players = $.merge(firstInnings.get('battingScoreCard').models.slice(0), secondInnings.get('battingScoreCard').models.slice(0));
		$.each(players, function(index, batsman){
			batsman.setStriker(false);
		});
		(_.filter(players, function(model){ return model.get('id') == nextBatsmanId; })[0]).setStriker(true);
	}
}

function updateTitle() {
	if(secondInnings.get('started'))
		var current_innings = secondInnings;
	else
		var current_innings = firstInnings;
	
	var overs_bowled = parseInt(current_innings.get('total_balls') / 6) + '.' + current_innings.get('total_balls') % 6;
	var title = current_innings.get('total_runs') + '/' + current_innings.get('total_wickets') + ' (' + overs_bowled + ' ov.) ' + current_innings.get('batting_team_name') + ' | Hitwicket';
	$(document).attr('title', title);
}

$(document).ready(function() {
    window.matchClient = new MatchClient(match_client_options);
    initialize();
    updateMatchEventsAndPerformActions(matchClient, true);
	$('#match_header_banner').html(matchHeaderView.render().el)
	
	$('#mini_batting_scorecard_container').html(miniBattingScoreCardView.render().el)
	$('#mini_bowling_scorecard_container').html(miniBowlingScoreCardView.render().el)
	firstInningsView.renderContent();
	secondInningsView.renderContent();
});