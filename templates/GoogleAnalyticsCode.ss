<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	ga('create', '$GAID', 'auto');
<% if IsDisplayFeatured %>ga('require', 'displayfeatures');<% end_if %>
	ga('send', 'pageview');
<% if MultiTrackersList %>
<% loop MultiTrackersList %>
	ga('create', '$ID', 'auto', {'name': '$Title'});
	ga('{$Title}.send', 'pageview');
	ga(function() { var $Title = ga.getByName('$Title'); });
<% end_loop %>
	ga(function() { var allTrackers = ga.getAll(); });﻿
<% end_if %>
</script>