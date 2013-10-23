window.MatchClient = Backbone.Model.extend({
	defaults : {
		base_url_to_fetch_data: null,
		previous_timsestamp: 0,
		ball_event_time: 30,
		events: new Array(),
		next_batsman_id : null,
		match_ended : 0
	},
	url : function () {
		return (this.get("base_url_to_fetch_data")+"?previous_timestamp="+this.get("previous_timestamp"));
	}
});

function updateMatchEventsAndPerformActions(matchClient, only_commentary) {
	matchClient.fetch({
		success: function (matchClient, response) {
			if(matchClient.get("match_ended") == 1)
				location.reload();
			processMatchEvents( matchClient.get("events"), only_commentary, matchClient.get("ball_event_time"));
		}
	});
}

function processMatchEvents(events, only_commentary, ball_event_time) {
    renderCommentarySummary(events);
	if(!only_commentary)
		updateRecordsAndRenderScorecards(events);
	setTimeout('updateMatchEventsAndPerformActions(matchClient, false)',(ball_event_time*1000));
}