<?php
include_once "TabTAPI.php";

$params = parse_ini_file("settings.ini.php");
$Account = $params["Account"];
$Password = $params["Password"];
$Club = $params["Club"];

if (isset($_POST["submit"])) {
	$Account = $_POST["Account"];
	$Password = $_POST["Password"];
	$Club = $_POST["Club"];

	$api = new TabTAPI($Account, $Password, $params["VTTL"], $params["Sporta"], $params["KAVVV"]);
	$api->SetCompetition($_POST["wsdlUrl"]);

	if (isset($_POST["Test"])) {
		$response = $api->Test();

	} else if (isset($_POST["GetSeasons"])) {
		$response = $api->GetSeasons();

	} else if (isset($_POST["GetClubTeams"])) {
		$response = $api->GetClubTeams($Club, $_POST["Season"]);

	} else if (isset($_POST["GetDivisionRanking"])) {
		$response = $api->GetDivisionRanking($_POST["DivisionId"], $_POST["WeekName"]);

	} else if (isset($_POST["GetClubs"])) {
		$response = $api->GetClubs($_POST["Season"], $Club, $_POST["ClubCategory"]);

	} else if (isset($_POST["GetMembers"])) {
		$response = $api->GetMembers(
			$Club,
			$_POST["Season"],
			$_POST["PlayerCategory"],
			$_POST["UniqueIndex"],
			$_POST["NameSearch"],
			isset($_POST["ExtendedInformation"]) ? $_POST["ExtendedInformation"] : 0);

	} else if (isset($_POST['GetMatches'])) {
		$response = $api->GetMatches(
			$_POST["DivisionId"],
			$Club,
			$_POST["Team"],
			$_POST["DivisionCategory"],
			$_POST["Season"],
			$_POST["WeekName"],
			$_POST["Level"],
			$_POST["ShowDivisionName"],
			isset($_POST["GetMatchDetails"]),
			$_POST["MatchId"]);
	}
}

function DisplayPost($index)
{
	if (!isset($_POST[$index])) {
		return "";
	}

	return htmlspecialchars($_POST[$index]);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>TabTAPI Test Form</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
</head>
<script>
	$(function() {
		function setClub(competition, clubId, e) {
			$('#Competition').val(competition);
			$('#Club').val(clubId);
			e.preventDefault();
		}

		$('#VttlClub').click(e => setClub('VTTL', 'OVL134', e));
		$('#SportaClub').click(e => setClub('Sporta', '4055', e));
	});
</script>
<body>

<div class="container">
	<br>

	<div class="panel panel-primary">
		<div class="panel-heading"><font size="+2">TabTAPI Test Form</font></div>
		<div class="panel-body">
			<div class="col-md-6">
				TabTAPI: <a href="http://api.frenoy.net/tabtapi-doc/index.html">API Documentation</a><br>
				Source: <a href="https://github.com/Laoujin/ttc-test-tabtapi">GitHub</a><br>
			</div>
			<div class="col-md-6">
				<h4>Competition Urls</h4>
				VTTL WSDL: <a href="<?=$params["VTTL"]?>"><?=$params["VTTL"]?></a><br>
				Sporta WSDL: <a href="<?=$params["Sporta"]?>"><?=$params["Sporta"]?></a><br>
				KAVVV WSDL: <a href="<?=$params["KAVVV"]?>"><?=$params["KAVVV"]?></a><br>
			</div>
		</div>
	</div>

	<?php
	if (isset($api)) {
		$alertClass = $api->IsSuccess() ? "success" : "danger";

		echo "<br>";

		echo '<div class="alert alert-'.$alertClass.' alert-dismissible fade in" role="alert">';
		echo '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
		echo "<h4>Function ".$api->GetLastCalled()."</h4>";
		echo "<h4>Request Parameters</h4>";
		echo "<pre>";
		print_r($api->GetLastParams());
		echo "</pre>";

		if ($api->IsSuccess()) {
			echo "<h4>Response</h4>";
			echo "<pre>";
			print_r($response);
			echo "</pre>";

		} else {
			echo "<h4>Error</h4>";
			echo "<pre>";
			print_r($api->GetLastError());
			echo "</pre>";
		}

		echo "</div>";
	}
	?>

	<form method="post" role="form">
	<input type="hidden" value="1" name="submit" />

	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading"><h4>Login</h4></div>
				<div class="panel-body">
					<div class="form-group">
						<label for="wsdlUrl">Competition:</label>
						<select class="form-control" name='wsdlUrl' id="Competition">
							<option value="VTTL" <?=(isset($_POST["wsdlUrl"]) && $_POST["wsdlUrl"] == "VTTL" ? "selected" : "")?>>VTTL</option>
							<option value="Sporta" <?=(isset($_POST["wsdlUrl"]) && $_POST["wsdlUrl"] == "Sporta" ? "selected" : "")?>>Sporta</option>
							<option value="KAVVV" <?=(isset($_POST["wsdlUrl"]) && $_POST["wsdlUrl"] == "KAVVV" ? "selected" : "")?>>KAVVV</option>
						</select>
					</div>
					<div class="form-group">
						<label for="Account">Account:</label>
						<input type="text" class="form-control" name='Account' value="<?=htmlspecialchars($Account)?>">
					</div>
					<div class="form-group">
						<label for="Password">Password:</label>
						<input type="text" class="form-control" name='Password' value="<?=htmlspecialchars($Password)?>">
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading"><h4>Common Parameters</h4></div>
				<div class="panel-body">
					Many functions accept these parameters:

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="form-group">
									<label for="Season">Season:</label> <small>(Get the id with GetSeasons. 2015-2016 = 16)</small>
									<input type="text" class="form-control" name='Season' value="<?=DisplayPost("Season")?>">
								</div>

								<div class="form-group">
									<label for="WeekName">WeekName:</label> <small>(ex: 15)</small>
									<input type="text" class="form-control" name='WeekName' value="<?=DisplayPost("WeekName")?>">
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<div class="form-group">
									<label for="Club">Club:</label> <small>(Erembodegem: VTTL: '<a href='#' id="VttlClub">OVL134</a>'. Sporta: '<a href='#' id="SportaClub">4055</a>')</small>
									<input type="text" class="form-control" name='Club' id='Club' value="<?=$Club?>">
								</div>

								<div class="form-group">
									<label for="DivisionId">DivisionId:</label>
									<input type="text" class="form-control" name='DivisionId' value="<?=DisplayPost("DivisionId")?>">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading"><h4>Test Connection</h4></div>
				<div class="panel-body">
					Dummy test function to verify connectivity.<br>
					<button type="submit" class="btn btn-primary" name="Test">Test</button>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading"><h4>GetSeasons</h4></div>
				<div class="panel-body">
					GetSeasons returns the list of seasons available in the TabT database.<br>
					<button type="submit" class="btn btn-primary" name="GetSeasons">GetSeasons</button>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading"><h4>GetClubTeams</h4></div>
				<div class="panel-body">
					GetClubTeams returns a list with all the teams of a given club.<br>
					Requires 'Club' to be filled in! 'Season' is optional.<br>

					<button type="submit" class="btn btn-primary" name="GetClubTeams">GetClubTeams</button>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading"><h4>GetDivisionRanking</h4></div>
				<div class="panel-body">
					Returns ranking of given division for a given week.<br>
					Requires 'DivisionId' to be filled in! 'WeekName' is optional.<br>

					<button type="submit" class="btn btn-primary" name="GetDivisionRanking">GetDivisionRanking</button>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading"><h4>GetClubs</h4></div>
				<div class="panel-body">
					Retrieve club list according to a given category.<br>
					'Season', 'ClubCategory' and 'Club' are optional.<br>

					<div class="form-group">
						<label for="ClubCategory">ClubCategory:</label>
						<input type="text" class="form-control" name='ClubCategory' value="<?=DisplayPost("ClubCategory")?>">
					</div>

					<button type="submit" class="btn btn-primary" name="GetClubs">GetClubs</button>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading"><h4>GetMembers</h4></div>
				<div class="panel-body">
					Returns list of members according to a search criteria (club, index or name).<br>
					All parameters are optional: 'Club', 'Season', 'PlayerCategory', 'UniqueIndex', 'NameSearch' and 'ExtendedInformation'.
					But at least one needs to be specified.<br>

					<div class="form-group">
						<label for="PlayerCategory">PlayerCategory:</label>
						<input type="text" class="form-control" name='PlayerCategory' value="<?=DisplayPost("PlayerCategory")?>">
					</div>
					<div class="form-group">
						<label for="UniqueIndex">UniqueIndex:</label>
						<input type="text" class="form-control" name='UniqueIndex' value="<?=DisplayPost("UniqueIndex")?>">
					</div>
					<div class="form-group">
						<label for="NameSearch">NameSearch:</label>
						<input type="text" class="form-control" name='NameSearch' value="<?=DisplayPost("NameSearch")?>">
					</div>
					<div class="checkbox">
					  <label><input type="checkbox" value="1" name="ExtendedInformation">ExtendedInformation</label>
					</div>

					<button type="submit" class="btn btn-primary" name="GetMembers">GetMembers</button>
				</div>
			</div>
		</div>

		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading"><h4>GetMatches</h4></div>
				<div class="panel-body">
					Returns list of matches and, if they are available, the match results.<br>
					All parameters are optional: 'DivisionId', 'Club', 'Team', 'DivisionCategory', 'Season', 'WeekName', 'Level', 'ShowDivisionName'.
					At least 'DivisionId', 'Club' or 'WeekName' needs to be specified.
					<br>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="ClubCategory">ClubCategory:</label>
								<input type="text" class="form-control" name='ClubCategory' value="<?=DisplayPost("ClubCategory")?>">
							</div>

							<div class="form-group">
								<label for="Team">Team (A, B, ...):</label>
								<input type="text" class="form-control" name='Team' value="<?=DisplayPost("Team")?>">
							</div>
							<div class="form-group">
								<label for="DivisionCategory">DivisionCategory:</label>
								<input type="text" class="form-control" name='DivisionCategory' value="<?=DisplayPost("DivisionCategory")?>">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="Level">Level:</label>
								<input type="text" class="form-control" name='Level' value="<?=DisplayPost("Level")?>">
							</div>
							<div class="form-group">
								<label for="ShowDivisionName">ShowDivisionName:</label>
								<select class="form-control" name='ShowDivisionName'>
									<option value="no" <?=(isset($_POST["ShowDivisionName"]) && $_POST["ShowDivisionName"] == "no" ? "selected" : "")?>>The division name is not given </option>
									<option value="yes" <?=(isset($_POST["ShowDivisionName"]) && $_POST["ShowDivisionName"] == "yes" ? "selected" : "")?>>The full division is given</option>
									<option value="short" <?=(isset($_POST["ShowDivisionName"]) && $_POST["ShowDivisionName"] == "short" ? "selected" : "")?>>A short name of the division is given</option>
								</select>
							</div>
							<div class="form-group">
								<label for="GetMatchDetails">Get match details:</label>
								<input type="checkbox" class="form-control" name='GetMatchDetails' value="1" <?=(isset($_POST["GetMatchDetails"]) ? 'checked="checked"' : '')?>>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="MatchId">MatchId:</label>
								<input type="text" class="form-control" name='MatchId' checked="<?=DisplayPost("MatchId")?>">
							</div>
						</div>
					</div>

					<button type="submit" class="btn btn-primary" name="GetMatches">GetMatches</button>
				</div>
			</div>
		</div>
	</div>

	</form>
</div>

<p>

</body>
</html>