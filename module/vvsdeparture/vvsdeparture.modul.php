<?php
class Vvsdeparture extends Modul {
	public function datenLaden($datenbank) {
		parent::datenLaden($datenbank);

		$stationIds = $this->getStationIds();
		$this->templateVars["departures"] = Array();

		$resultCount = $this->templateVars["strings"]["vvsdeparture-resultCount"];

		foreach ($stationIds as $id) {
			$data = $this->requestDepartures($id, $resultCount);
			$departures = $this->parseResult($data);
		}

	}

	private function getStationIds() {
		if (isset($this->templateVars["strings"]["vvsdeparture-stationCount"])) {
			$count = $this->templateVars["strings"]["vvsdeparture-stationCount"];
		} else {
			return Array();
		}

		if (is_numeric($count)) {
			$count = (int) $count;
		} else {
			return Array();
		}

		$result = Array();

		for ($i = 0; $i < $count; $i++) {
			if (isset($this->templateVars["strings"]["vvsdeparture-stationId" . $i])) {
				$stationId = $this->templateVars["strings"]["vvsdeparture-stationId" . $i];
				if (is_numeric($stationId)) {
					$result[] = $stationId;
				}
			}
		}

		return $result;
	}

	private function requestDepartures($stationId, $resultCount) {
		$url = $this->getURL($stationId, $resultCount);
		$json = file_get_contents($url);
		return json_decode($json, true);
	}

	private function parseResult($result) {
		$departures = [];

		$station = $result["dm"]["points"]["point"]["object"];

		foreach ($result["departureList"] as $departure) {
			$countdown = $departure["countdown"];

			if (isset($departure["realDateTime"])) {
				// Real Date Time
				$hd = $departure["realDateTime"]["hour"];
				if (strlen($hd) == 1) {
					$hd = "0" . $hd;
				}
				$md = $departure["realDateTime"]["minute"];
				if (strlen($md) == 1) {
					$md = "0" . $md;
				}
			}

			// Scheduled Date Time
			$h = $departure["dateTime"]["hour"];
			if (strlen($h) == 1) {
				$h = "0" . $h;
			}
			$m = $departure["dateTime"]["minute"];
			if (strlen($m) == 1) {
				$m = "0" . $m;
			}

			if (isset($departure["realDateTime"]) && ($hd != $h || $md != $m)) {
				$delayed = true;
				$dep = $hd. ":" . $md;
			} else {
				$delayed = false;
				$dep = $h. ":" . $m;
			}

			$line = $departure["servingLine"]["number"];
			$linetype = $departure["servingLine"]["name"];
			$destination = $departure["servingLine"]["direction"];
			
			$departures[] = Array("countdown" => $countdown, "delayed" => $delayed,
				"departure" => $dep, "line" => $line, "linetype" => $linetype, 
				"destination" => $destination);
		}
		
		$this->templateVars["departures"][$station] = $departures;
	}

	private function getURL($stationId, $resultCount) {
		$url = "http://www2.vvs.de/vvs/widget/XML_DM_REQUEST?";
		$url .= 'zocationServerActive=1';
		$url .= '&lsShowTrainsExplicit=1';
		$url .= '&stateless=1';
		$url .= '&language=de';
		$url .= '&SpEncId=0';
		$url .= '&anySigWhenPerfectNoOtherMatches=1';
		$url .= '&limit=' . $resultCount;
		$url .= '&depArr=departure';
		$url .= '&type_dm=any';
		$url .= '&anyObjFilter_dm=2';
		$url .= '&deleteAssignedStops=1';
		$url .= '&name_dm=' . $stationId;
		$url .= '&mode=direct';
		$url .= '&dmLineSelectionAll=1';
		$url .= '&itdDateYear=' . date("Y");
		$url .= '&itdDateMonth=' . date("m");
		$url .= '&itdDateDay=' . date("d");
		$url .= '&itdTimeHour=' . date("H");
		$url .= '&itdTimeMinute=' . date("i");
		$url .= '&useRealtime=1';
		$url .= '&outputFormat=json';
		$url .= '&coordOutputFormat=WGS84[DD.ddddd]';

		return $url;
	}
}
?>
