<?
    function parse($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36');
        curl_setopt($curl, CURLOPT_REFERER, 'http://www.yandex.ru');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }
    //парсинг 1 страницы
    $site = parse('https://match.uefa.com/v5/matches?fromDate=2022-07-28&toDate=2022-07-28&utcOffset=3&order=ASC&offset=0&limit=500&competitionId=18%2C24%2C39%2C14%2C27%2C38%2C22%2C19%2C2014%2C2017%2C5%2C28%2C9%2C1%2C13%2C3%2C25%2C2018%2C101%2C17%2C2008%2C23%2C2019%2C2020%2C2021%2C2022');
    $matches = json_decode($site);
    $parsedMatches = [];
    foreach($matches as $match){
        $date = $match->fullTimeAt;
        $homeTeam = $match->homeTeam->internationalName;
        $guestTeam = $match->awayTeam->internationalName;
        $matchId = $match->id;
        $matchUrl = 'https://www.uefa.com/api/v1/linkrules/match/'.$matchId;
        $arr = array(
            "date"=>"$date",
            "homeTeam"=>"$homeTeam",
            "guestTeam"=>"$guestTeam",
            "matchUrl"=>"$matchUrl",
        );
        array_push($parsedMatches, $arr);
    }
    var_dump($parsedMatches);
    //парсинг 2 страницы
    $currentMatch = parse('https://match.uefa.com/v5/matches/2035159/lineups');
    $currentMatchArr = json_decode($currentMatch);
    $guestTeamPlayers = [];
    foreach($currentMatchArr->awayTeam->bench as $player){
        $number = $player->jerseyNumber;
        $name = $player->player->internationalName;
        $arr = array(
            "name"=>"$name",
            "number"=>"$number",
        );
        array_push($guestTeamPlayers, $arr);
    }
    $homeTeamPlayers = [];
    foreach($currentMatchArr->homeTeam->bench as $player){
        $number = $player->jerseyNumber;
        $name = $player->player->internationalName;
        $arr = array(
            "name"=>"$name",
            "number"=>"$number",
        );
        array_push($homeTeamPlayers, $arr);
    }
    $matchDetails = array(
        "guest"=>$guestTeamPlayers,
        "home"=>$homeTeamPlayers,
    );
    var_dump($matchDetails);
?>
