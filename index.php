<?php
include_once "TabTAPI.php";

$month = intval(date("n"));
$year = intval(date("Y"));
if ($month > 5) {
	$curSeason = $year - 2000 + 1;
} else {
	$curSeason = $year - 2000;
}
$curSeasonDesc = ($curSeason + 2000 - 1) . '-' . ($curSeason + 2000) . ' = ' . $curSeason;
// 2015-2016 = 16
// 2016-2017 = 17
// 2018-2019 = 19
define('CURRENT_SEASON', $curSeason);


$params = parse_ini_file("settings.ini.php");
$Account = $params["Account"];
$Password = $params["Password"];
$Club = $params["ClubVTTL"];

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
		$response = $api->GetDivisionRanking($_POST["DivisionId"], $_POST["WeekName"], $_POST["RankingSystem"]);

	} else if (isset($_POST["GetClubs"])) {
		$response = $api->GetClubs($_POST["Season"], $Club, $_POST["ClubCategory"]);

	} else if (isset($_POST["GetMembers"])) {
		$response = $api->GetMembers(
			$Club,
			$_POST["Season"],
			$_POST["PlayerCategory"],
			$_POST["UniqueIndex"],
			$_POST["NameSearch"],
			isset($_POST["ExtendedInformation"]) ? $_POST["ExtendedInformation"] : 0,
			isset($_POST["RankingPointsInformation"]));

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

	} else if (isset($_POST["GetTournaments"])) {
		$response = $api->GetTournaments(
			$_POST["Season"],
			$_POST["TournamentUniqueIndex"],
			isset($_POST["WithResults"]),
			isset($_POST["WithRegistrations"]));
	} else if (isset($_POST["GetDivisions"])) {
		$response = $api->GetDivisions(
			$_POST["Season"],
			$_POST["Level"],
			$_POST["ShowDivisionName"]);
	} else if (isset($_POST["GetMatchSystems"])) {
		$response = $api->GetMatchSystems($_POST["MatchSystemUniqueIndex"]);
	} else if (isset($_POST["GetPlayerCategories"])) {
		$response = $api->GetPlayerCategories(
			$_POST["Season"],
			$_POST["PlayerCategoryUniqueIndex"],
			$_POST["ShortNameSearch"],
			$_POST["RankingCategory"]);
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="styles.css">
<script src="scripts.js"></script>
</head>
<body>

<div class="container">
	<br>

	<div class="panel panel-primary">
		<div class="panel-heading"><font size="+2">TabTAPI Test Form</font></div>
		<div class="panel-body">
			<div class="col-md-6">
				<a href="http://tabt.frenoy.net/index.php">TabT-Api Docs</a><br>
				<a href="https://github.com/gfrenoy/TabT-API">TabT-API Source</a><br>
				<a href="https://github.com/Laoujin/ttc-test-tabtapi">Source of this</a><br>
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
			echo '<div class="code-container">';
			echo "<pre id=\"codeBlock\">";
			if ($_POST['OutputType'] === 'print_r') {
				print_r($response);
			} else if ($_POST['OutputType'] === 'XML') {
				$xml = $api->_client->__getLastResponse();
				$dom = new DOMDocument();
				$dom->preserveWhiteSpace = false;
				$dom->formatOutput = true;
				$dom->loadXML($xml);
				echo htmlspecialchars($dom->saveXML());
			} else {
				echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
			}
			echo "</pre>";
			echo "<button class=\"copy-button\" onclick=\"copyToClipboard()\">Copy</button>";
			echo "</div>";

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
				<div class="panel-heading"><h4>Login (optional)</h4></div>
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
									<label for="Season">Season:</label> <small>(Get the id with GetSeasons. <?=$curSeasonDesc?>)</small>
									<input type="text" class="form-control" name='Season' value="<?=(DisplayPost("Season") ? DisplayPost("Season") : CURRENT_SEASON)?>">
								</div>

								<div class="form-group">
									<label for="WeekName">WeekName:</label> <small>(ex: 15)</small>
									<input type="text" class="form-control" name='WeekName' value="<?=DisplayPost("WeekName")?>">
								</div>
							</div>

							<div class="form-group">
								<label for="OutputType">Format Response As:</label>
								<select class="form-control" name='OutputType' id="OutputType">
									<option value="print_r" <?=(isset($_POST["OutputType"]) && $_POST["OutputType"] == "print_r" ? "selected" : "")?>>print_r</option>
									<option value="JSON" <?=(isset($_POST["OutputType"]) && $_POST["OutputType"] == "JSON" ? "selected" : "")?>>JSON</option>
									<option value="XML" <?=(isset($_POST["OutputType"]) && $_POST["OutputType"] == "XML" ? "selected" : "")?>>XML</option>
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<div class="form-group">
									<label for="Club">Club:</label>
									<small>
										(<?=$params["ClubName"]?>: VTTL: '<a href='#' id="VttlClub"><?=$params["ClubVTTL"]?></a>'.
										Sporta: '<a href='#' id="SportaClub"><?=$params["ClubSporta"]?></a>')
									</small>
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

					<div class="form-group">
						<label for="RankingSystem">RankingSystem:</label>
						<input type="text" class="form-control" name='RankingSystem' value="<?=DisplayPost("RankingSystem")?>">
					</div>

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

		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading"><h4>GetTournaments</h4></div>
				<div class="panel-body">
					Requires a UniqueIndex to get results/registrations.<br>

					<div class="form-group">
						<label for="TournamentUniqueIndex">TournamentUniqueIndex:</label>
						<input type="text" class="form-control" name='TournamentUniqueIndex' value="<?=DisplayPost("TournamentUniqueIndex")?>">
					</div>
					<div class="checkbox">
						<label><input type="checkbox" value="1" name="WithResults" <?=(isset($_POST["WithResults"]) ? 'checked="checked"' : '')?>>Get with results</label>
					</div>
					<div class="checkbox">
						<label><input type="checkbox" value="1" name="WithRegistrations" <?=(isset($_POST["WithRegistrations"]) ? 'checked="checked"' : '')?>>Get with registrations</label>
					</div>

					<button type="submit" class="btn btn-primary" name="GetTournaments">GetTournaments</button>
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
						<label><input type="checkbox" value="1" name="ExtendedInformation" <?=(isset($_POST["ExtendedInformation"]) ? 'checked="checked"' : '')?>>ExtendedInformation (Login Required)</label>
					</div>
					<div class="checkbox">
						<label><input type="checkbox" value="1" name="RankingPointsInformation" <?=(isset($_POST["RankingPointsInformation"]) ? 'checked="checked"' : '')?>>RankingPointsInformation</label>
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
								<input type="text" class="form-control" name='MatchId' value="<?=DisplayPost("MatchId")?>">
							</div>
						</div>
					</div>

					<button type="submit" class="btn btn-primary" name="GetMatches">GetMatches</button>
				</div>
			</div>
		</div>
	</div>


	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading"><h4>GetDivisions</h4></div>
				<div class="panel-body">
					<div class="form-group">
						<label for="Level">Level:</label>
						<input type="text" class="form-control" name='Level' value="<?=DisplayPost("Level")?>">
					</div>
					<div class="form-group">
						<label for="ShowDivisionName">Show DivisionName:</label>
						<select class="form-control" name='ShowDivisionName'>
							<option value="no" <?=(isset($_POST["ShowDivisionName"]) && $_POST["ShowDivisionName"] == "no" ? "selected" : "")?>>No</option>
							<option value="yes" <?=(isset($_POST["ShowDivisionName"]) && $_POST["ShowDivisionName"] == "yes" ? "selected" : "")?>>Yes</option>
							<option value="short" <?=(isset($_POST["ShowDivisionName"]) && $_POST["ShowDivisionName"] == "short" ? "selected" : "")?>>Short</option>
						</select>
					</div>
					<button type="submit" class="btn btn-primary" name="GetDivisions">GetDivisions</button>
				</div>
			</div>
		</div>


		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading"><h4>GetMatchSystems</h4></div>
				<div class="panel-body">
					<div class="form-group">
						<label for="MatchSystemUniqueIndex">UniqueIndex:</label>
						<input type="text" class="form-control" name='MatchSystemUniqueIndex' value="<?=DisplayPost("MatchSystemUniqueIndex")?>">
					</div>
					<button type="submit" class="btn btn-primary" name="GetMatchSystems">GetMatchSystems</button>
				</div>
			</div>
		</div>


		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading"><h4>GetPlayerCategories</h4></div>
				<div class="panel-body">
					<div class="form-group">
						<label for="PlayerCategoryUniqueIndex">UniqueIndex:</label>
						<input type="text" class="form-control" name='PlayerCategoryUniqueIndex' value="<?=DisplayPost("PlayerCategoryUniqueIndex")?>">
					</div>
					<div class="form-group">
						<label for="ShortNameSearch">ShortNameSearch:</label>
						<input type="text" class="form-control" name='ShortNameSearch' value="<?=DisplayPost("ShortNameSearch")?>">
					</div>
					<div class="form-group">
						<label for="RankingCategory">RankingCategory <small>(1=Heren, 2=Dames)</small>:</label>
						<input type="text" class="form-control" name='RankingCategory' value="<?=DisplayPost("RankingCategory")?>">
					</div>
					<button type="submit" class="btn btn-primary" name="GetPlayerCategories">GetPlayerCategories</button>
				</div>
			</div>
		</div>
	</form>
</div>

<p>

</body>
</html>
