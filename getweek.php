<?
if (isset($_POST['week'])) {
		$week = $_POST['week'];
		$yg_query = "SELECT * FROM games WHERE league=$leagueid AND year=$year AND week=".mysqli_real_escape_string($conn,$week)." AND (home=$myteamid OR away=$myteamid)";
		$score_query = "SELECT * FROM games WHERE league=$leagueid AND year=$year AND week=$week";
		$header = "Week ".$week;
	} else {
		$week = 1;
		$yg_query = "SELECT * FROM games WHERE league=$leagueid AND year=$year AND week=1 AND (home=$myteamid OR away=$myteamid)";
		$score_query = "SELECT * FROM games WHERE league=$leagueid AND year=$year AND week=1";
		$header = "Week 1";
	}
	
	if (isset($_POST['team'])) {
		$schedteam = $_POST['team'];
		$score_query = "SELECT * FROM games WHERE league=$leagueid AND year=$year AND (home=".mysqli_real_escape_string($conn,$schedteam)." OR away=".mysqli_real_escape_string($conn,$schedteam).") AND week BETWEEN 1 AND 16 ORDER BY week";
		$schedteam_result = mysqli_query($conn,"SELECT location,teamname FROM team WHERE id=$schedteam");
		
		$schedData = mysqli_fetch_array($schedteam_result);
		$header = $schedData['location']." ".$schedData['teamname']." "." Schedule";
	}
	
?>