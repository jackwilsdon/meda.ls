$("a.pop").click(function (e) {

	// Whoops! (was in presentation...)
	//alert($(document).width());

	if ($(document).width() < 1440) {
		$("a.pop").popover({
			trigger: "manual",
			placement: "left"
		});
	} else {
		$("a.pop").popover({
			trigger: "manual",
			placement: "right"
		});
	}

	var placeName = $(this).parent().parent().html();
	var placeName = placeName.split("<!--");
	var placeName = placeName[1].split("-->");
	var placeName = placeName[0];
	
	var t = $(this);

	e.preventDefault();
	var popover = $('a.active')[0];
	
	if (popover != null) {
	
		$(popover).popover("hide");
		$(popover).removeClass('active');
		if (this == popover) return;
		
	}
	
	$(this).addClass('active');
	$(this).popover("show");

	$(".popover-content").find("a").click(function (e) {
		e.preventDefault();
		$("a.pop").popover("hide");
		$("a.pop").removeClass('active');
		
		var value = $(this).text();
		
		$.get("athletes.php", {
			place: placeName,
			medal: value
		}, function (returned_data) {
		
			$('.modal-header').html("<button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>\n<h3>" + value + " medalists</h3>");
			$('.modal-body').html(returned_data);
			$('#modal').modal('show');
			$('.modal-body').find("a").click(function () {
			
				var athlete = $(this).text();
				var twitter = athlete.split(' ').join('').toLowerCase();
				
				$('#modal').modal('hide');
				
				$.get("bio.php", {
					name: athlete,
					medal: value
				}, function (returned_data) {
					if (returned_data == null) {
						alert("A serious error has occurred. Are you connected to the internet?");
					}
					$.get("youtube.php", {
						name: athlete,
						medal: value
					}, function (ytl) {
						var medals = "<img src=\"athleteMedal.php?type=gold&name=" + athlete + "\"> <img src=\"athleteMedal.php?type=silver&name=" + athlete + "\"> <img src=\"athleteMedal.php?type=bronze&name=" + athlete + "\">";
						
						$('.modal-header').html("<button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>\n<h3>" + athlete + " " + medals + " "+ytl+" <a href=\"http://twitter.com/#!/search/%23" + twitter + "\" target=\"_blank\"><img src=\"http://www.wecando.biz/i/icon_twitter_bird.png\"></a></h3>");
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
					});					
				});
				
			});
			
		});
		
	});
	
})