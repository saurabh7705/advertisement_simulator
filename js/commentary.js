var other_commentary_array = ['all_out', 'active_manager', 'post_innings', 'innings_break', 'post_match', 'toss', 'theme_song'];
var batsman_id_who_came_in_crease = "";
window.Commentary = Backbone.Model.extend({
      defaults: {
          innings_number: 1,
          class_name: "",
          over : "",
          ball : "",
          runs : "",
          runs_string : "",
          batsman_name : "",
          bowler_name : "",
          commentry: "",
          fielding_class: "",
          ball_power: null,
          bat_power: null,
          bowler_luck: null,
          batsman_luck: null,
          slog_mod_wicket: null,
          percent_wicket_event: null,
          run_event_modifier: null,
          required_run_rate: null,
          commentary_id: null
      }
});

window.CommentaryView = Backbone.View.extend({
      initialize: function() {
          this.template = _.template($('#runs_or_out_commentary').html());
      },
      render: function() {          
          var class_name = this.model.get("class_name");
          if(class_name != 'title') {
            if( ($.inArray(class_name, other_commentary_array)) != -1)
              this.template = _.template($('#other_commentary').html());

			if(class_name == 'pre_innings'){
				this.template = _.template($('#pre_innings_commentary').html());
				if(this.model.get("innings_number") == 1)
					this.model.set({commentry: firstBattingTeamName})
				else
				 	this.model.set({commentry: secondBattingTeamName})
			}

            var renderedContent = this.template(this.model.toJSON());
            $(this.el).addClass(class_name).html(renderedContent);
          }
          return this;
      }
});

window.CommentaryListView = Backbone.View.extend({
	initialize: function() {
		_.bindAll(this, 'render');
		this.collection.bind("reset", this.render, this)
	},
	render: function(){
		var commentaries = this.collection,
		listView = $(this.el);
		$(this.el).empty();
		commentaries.each( function(commentary) {
			var class_name = commentary.get("class_name");
			var commentaryView = new CommentaryView({model:commentary});			
			var commentaryElement = commentaryView.render().el;			
			listView.append(commentaryElement);
		});
		return this;
	},
	reset: function() {
		this.render();
		return this;
	}
});

window.SummaryCommentaryListView = Backbone.View.extend({
	initialize: function() {
		_.bindAll(this, 'render');
		this.collection.bind("add", this.render, this)
	},
	render: function(){
		var innerHtml = [];
		var models = this.collection.models.slice(0);
		$.each(models.reverse(), function(index, commentary) {
			var commentaryView = new CommentaryView({model:commentary});
			innerHtml.push(commentaryView.render().el);
		});
		var renderedContent = $('<div></div>');
		$.each(innerHtml, function(index, content) {
			$(renderedContent).append(content);
		});
		$(this.el).html(renderedContent.html());
		return this;
	}
});


window.CommentaryList = Backbone.Collection.extend({
    model: Commentary
});

function fetchAndRenderCommentaries(events) {
    var commentaryList = new CommentaryList;
    var commentaryListView = new CommentaryListView({collection : commentaryList});
    $.each(events, function(index, event){
      var commentary = new Commentary(event);
      commentaryList.add(commentary);
    });
    $('#commentary_tab').append(commentaryListView.render().el);
}

function renderCommentarySummary(events) {
	$.each(events, function(index, event){

    if(event.event_type == "new_batsman")
      batsman_id_who_came_in_crease = event.batsman_id;
    else
      batsman_id_who_came_in_crease = "";

		var commentary = new Commentary(event);		
		if(summaryCommentaryList.models.length == 20)
			summaryCommentaryList.shift();
		summaryCommentaryList.add(commentary);

	});

	$('#summary_commentary').html(summaryCommentaryListView.render().el);
	fetchAndRenderCommentaries(events);

  if(isMobileRequest == 0)
    checkAndMakeNewBatsmanEntry();
}

function checkAndMakeNewBatsmanEntry() {
    if(batsman_id_who_came_in_crease != "") {
        var ajaxOpts = {
                type: "post",
                data : {player_id: batsman_id_who_came_in_crease},
                url: stats_modal_url,
                success: function(data) {
                  if(data != 'error') {
                    $('#new_batsman_modal_live_match').modal('hide');
                    $('#new_batsman_modal_live_match').html(data);
					$('#new_batsman_modal_live_match').modal();
                  }
                  batsman_id_who_came_in_crease = "";
                }
            };
	    $.ajax(ajaxOpts);
    }
}