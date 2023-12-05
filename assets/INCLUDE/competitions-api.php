<?php

class Jogo
{
    public $date;
    public $nameTeamOne;
    public $flagTeamOne;
    public $scoreTeamOne;
    public $nameTeamTwo;
    public $flagTeamTwo;
    public $scoreTeamTwo;

    public function __construct($_date, $_nameTeamOne, $_flagTeamOne, $_scoreTeamOne, $_nameTeamTwo, $_flagTeamTwo, $_scoreTeamTwo)
    {
        $createDate = date_create($_date);
        $formattedDate = date_format($createDate, 'd/m/Y');

        $this->date = $formattedDate;
        $this->nameTeamOne = $_nameTeamOne;
        $this->flagTeamOne = $_flagTeamOne;
        $this->scoreTeamOne = $_scoreTeamOne;
        $this->nameTeamTwo = $_nameTeamTwo;
        $this->flagTeamTwo = $_flagTeamTwo;
        $this->scoreTeamTwo = $_scoreTeamTwo;
    }
}

class rank
{
    public $position;
    public $name;
    public $playedGames;
    public $won;
    public $draw;
    public $lost;
    public $points;
    public $goalsFor;
    public $goalsAgainst;
    public $goalDiference;

    public function __construct($_position, $_name, $_playedGames, $_won, $_draw, $_lost, $_points, $_goalsFor, $_goalsAgainst, $_goalDiference)
    {
        $this->position = $_position;
        $this->name = $_name;
        $this->playedGames = $_playedGames;
        $this->won = $_won;
        $this->draw = $_draw;
        $this->lost = $_lost;
        $this->points = $_points;
        $this->goalsFor = $_goalsFor;
        $this->goalsAgainst = $_goalsAgainst;
        $this->goalDiference = $_goalDiference;
    }
}

class player
{
    public $name;
    public $team;
    public $goals;
    public $assists;
    public $penalties;

    public function __construct($_name, $_team, $_goals, $_assists, $_penalties)
    {
        $this->name = $_name;
        $this->team = $_team;
        $this->goals = $_goals;
        $this->assists = $_assists;
        $this->penalties = $_penalties;
    }
}
