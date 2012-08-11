$(document).ready(function(){
	var obj = $('input[data-provide=typeahead]');
	$.get("typeahead.php", function(result) {
		var str = eval("(" + result + ')');
		obj.typeahead({
			source: str
		});
				
	});
});

function submitForm(e)
{
	e.preventDefault();
}

$("#search").keyup(function(e){
	if(event.keyCode == 13){
		doStuff();
	}
});

$("#searchBTN").click(function(e){
	doStuff();
});

function doStuff() {
	var obj = $("#search");
	setTimeout(function(){
		var athlete = obj.val();
		var twitter = athlete.split(' ').join('').toLowerCase();
		var value = "gold";
		$.get("bio.php", {
			name: athlete
		}, function (returned_data) {
			$.get("youtube.php", {
				name: athlete,
				medal: value
			}, function (ytl) {
				$.get("getTeam.php", {
					name: athlete
				}, function (team) {
					if (team == "0") {
						alert("Athlete not found!");
					} else {
						var medals = "<img src=\"athleteMedal.php?type=gold&name=" + athlete + "\"> <img src=\"athleteMedal.php?type=silver&name=" + athlete + "\"> <img src=\"athleteMedal.php?type=bronze&name=" + athlete + "\">";
						
						$('.modal-header').html("<button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>\n<h3>" + team + athlete + " " + medals + " "+ytl+" <a href=\"http://twitter.com/#!/search/%23" + twitter + "\" target=\"_blank\"><img src=\"http://www.wecando.biz/i/icon_twitter_bird.png\"></a></h3>");
						$('.modal-body').html("<p>" + returned_data + "</p>");
						
						setTimeout(
							function () {
								$('#modal').modal('show');
								
								$.get("getSport.php", {
									name: athlete
								}, function (sport) {
								
									displayMap(sport);
								
								})
						}, 500);
					}
				});
			});
		});
	}, 50);
}