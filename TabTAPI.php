<?php
class TabTAPI
{
	private $_credentials;

	private $_urlVTTL;
	private $_urlSporta;
	private $_urlKAVVV;
	private $_wsdlUrl;

	private $_lastCallSuccess;
	private $_lastParams;
	private $_lastFunctionName;
	private $_lastError;

	function TabTAPI($account, $password, $urlVTTL, $urlSporta, $urlKAVVV)
	{
		$this->_credentials = new Credentials($account, $password);

		$this->_urlVTTL = $urlVTTL;
		$this->_urlSporta = $urlSporta;
		$this->_urlSporta = $urlSporta;
		$this->_urlKAVVV = $urlKAVVV;

		$this->SetCompetition("VTTL");
	}

	function Test()
	{
		$this->_lastParams = array(
			"Credentials" => $this->_credentials
		);
		return $this->soapCall("Test");
	}

	function SetCompetition($comp)
	{
		switch ($comp)
		{
			case "VTTL":
				$this->_wsdlUrl = $this->_urlVTTL;
				break;

			case "Sporta":
				$this->_wsdlUrl = $this->_urlSporta;
				break;

			case "KAVVV":
				$this->_wsdlUrl = $this->_urlKAVVV;
				break;

			default:
				throw new Exception("SetCompetition expects 'VTTL', 'Sporta' or 'KAVVV'");
		}
	}

	function GetSeasons()
	{
		$this->_lastParams = array(
			"Credentials" => $this->_credentials
		);
		return $this->soapCall("GetSeasons");
	}

	function GetClubTeams($club, $season)
	{
		$this->_lastParams = array(
			"Credentials" => $this->_credentials,
			"Club" => $club,
			"Season" => $season
		);
		return $this->soapCall("GetClubTeams");
	}

	function GetDivisionRanking($divisionId, $weekName, $rankingSystem)
	{
		$this->_lastParams = array(
			"Credentials" => $this->_credentials,
			"DivisionId" => $divisionId,
			"WeekName" => $weekName,
			"RankingSystem" => $rankingSystem
		);

		$result = $this->soapCall("GetDivisionRanking");
		return $result;
	}

	function GetClubs($season, $club, $clubCategory)
	{
		$this->_lastParams = array(
			"Credentials" => $this->_credentials,
			"Season" => $season ? $season : null,
			"ClubCategory" => $clubCategory ? $clubCategory : null,
			"Club" => $club
		);

		$clubs = $this->soapCall("GetClubs");
		return $clubs;
	}

	function GetMembers($club, $season, $playerCategory, $uniqueIndex, $nameSearch, $extendedInformation, $rankingPointsInformation)
	{
		$this->_lastParams = array(
			"Credentials" => $this->_credentials,
			"Club" => $club ? $club : null,
			"Season" => $season ? $season : null,
			"PlayerCategory" => $playerCategory ? $playerCategory : null,
			"UniqueIndex" => $uniqueIndex ? $uniqueIndex : null,
			"NameSearch" => $nameSearch,
			"ExtendedInformation" => $extendedInformation ? 1 : 0,
			"RankingPointsInformation" => $rankingPointsInformation ? true : false
		);
		//print_r($this->_lastParams);
		return $this->soapCall("GetMembers");
	}

	function GetMatches($divisionId, $club, $team, $divisionCategory, $season, $weekName, $level, $showDivisionName, $withDetails, $matchId)
	{
		$this->_lastParams = array(
			"Credentials" => $this->_credentials,
			"DivisionId" => $divisionId ? $divisionId : null,
			"Club" => $club ? $club : null,
			"Team" => $team ? $team : null,
			"DivisionCategory" => $divisionCategory ? $divisionCategory : null,
			"Season" => $season ? $season : null,
			"WeekName" => $weekName,
			"Level" => $level ? $level : null,
			"ShowDivisionName" => $showDivisionName, /* no, yes, short */
			"WithDetails" => $withDetails,
			"MatchId" => $matchId
		);
		return $this->soapCall("GetMatches");
	}

	private function soapCall($functionName)
	{
		try {
			$this->_lastCallSuccess = true;
			$this->_lastError = "";
			$this->_lastFunctionName = $functionName;

			$this->_client = new SoapClient($this->_wsdlUrl);
			$result = $this->_client->__soapCall($functionName, array($this->_lastParams));
			return $result;

		} catch (SoapFault $fault) {
			$this->_lastCallSuccess = false;
			$this->_lastError = $fault->faultcode . ": " . $fault->faultstring;
			return null;
		}
	}

	function IsSuccess()
	{
		return $this->_lastCallSuccess;
	}

	function GetLastParams()
	{
		return $this->_lastParams;
	}

	function GetLastCalled()
	{
		return $this->_lastFunctionName;
	}

	function GetLastError()
	{
		return $this->_lastError;
	}
}

class Credentials
{
    function Credentials($account, $password)
    {
        $this->Account = $account;
        $this->Password = $password;
    }
}
?>
