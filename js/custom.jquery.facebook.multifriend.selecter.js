var fb_access_token;
var close_friends = [];
function loadMultiFriendSelecterModal() {
    FB.login(function(response) {
        if (response.authResponse) {            
            fb_access_token = response.authResponse.accessToken;
            FB.api('/me/friendlists?fields=members&list_type=close_friends', function(response) {
              var members = (response.data[0]).members;
              if(members != undefined) {
                var close_friends_object = (response.data[0]).members.data;
                for (var i=0; i<close_friends_object.length; i++){
                  var name = (close_friends_object[i]).name;
                  close_friends.push(name);
                }
              }
            });
            FB.api('/me', function(response) {
                $("#facebook_friends_container_modal #modal_error_response").hide();
                $("#facebook_friends_container_modal #modal_loader").hide();
                $(".modal-footer").show();
                $('#facebook_friends_container_modal').modal();
                $("#facebook_friends_container_modal #jfmfs-container").jfmfs({
                    labels: {
                        selected: "Selected",
                        filter_default: "Start typing a name",
                        filter_title: "Find Friends:",                        
                        all: "All",
                        max_selected_message: "{0} of {1} selected"
                        // message to display showing how many items are already selected like: "{0} of {1} chosen"
                    },
                    friend_fields:"id,name",
                    sorter:function(a, b) {
                                var x,y;
                                if($.inArray(a.name, close_friends) != -1){
                                  x = 'a';
                                  y = b.name.toLowerCase();
                                }
                                else if($.inArray(b.name, close_friends) != -1){
                                  x = a.name.toLowerCase();
                                  y = 'a';
                                }
                                else if(($.inArray(a.name, close_friends) != -1) && ($.inArray(b.name, close_friends) != -1) ){
                                  x = 'a';
                                  y = 'a';
                                }
                                else {
                                  x = a.name.toLowerCase();
                                  y = b.name.toLowerCase();
                                }
                                return ((x < y) ? -1 : ((x > y) ? 1 : 0));
                        }
                });
            });
        }
    }, {scope: 'user_location,email,publish_stream,read_friendlists,offline_access'});
}

function sendFbInvitations(url,custom_msg) {
  var friendSelector  = $("#jfmfs-container").data('jfmfs');
  var selectedFriends = friendSelector.getSelectedIds();
  if(selectedFriends.length > 0) {
    $("#facebook_friends_container_modal #modal_loader").show();
    $("#facebook_friends_container_modal #modal_error_response").hide();
    $(".modal-footer").hide();
    $.ajax({
      'type':'post',
      'data':{'selected_friend_ids':selectedFriends,'access_token':fb_access_token,'custom_msg':custom_msg},
      'url':url,
      'cache':false,
      'success':function(message){
                    $('#facebook_friends_container_modal').modal('hide');
                    var html = message+'<a class="close" data-dismiss="alert" href="#">&times;</a>';
                    $('#invite_response').html(html).show();
                }
    });
  }
  else{
    var html = 'Please select atleast one friend first';
    $("#facebook_friends_container_modal #modal_error_response").html(html).show();
  }
}