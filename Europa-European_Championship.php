<?php
include_once('../futebol_score/assets/INCLUDE/head.php');
include('../futebol_score/assets/INCLUDE/calendar.php');
include('../futebol_score/assets/INCLUDE/football-api.php');
include('../futebol_score/assets/INCLUDE/competitions-api.php');
include('../futebol_score/assets/INCLUDE/chave.php');
include('../futebol_score/assets/INCLUDE/menu.php');



/* começar com o calendario fazer com q so apareçam so os jogos do dia atual*/
$day = $daySelect;
$month = $monthSelect;
$year = $yearSelec;
$matches = conexaoAPI("http://api.football-data.org/v4/competitions/2018/matches?dateFrom={$year}-{$month}-{$day}&dateTo={$year}-{$month}-{$day}", $chave);
$arrayGames = array();

foreach ($matches->matches as $match) {
    $match->id = new Jogo($match->utcDate, $match->homeTeam->shortName, $match->homeTeam->crest, $match->score->fullTime->home, $match->awayTeam->shortName, $match->awayTeam->crest, $match->score->fullTime->away);
    $arrayGames[] = $match->id;
}

$ranks = conexaoAPI("http://api.football-data.org/v4/competitions/EC/standings?season={$year}", $chave);
$arrayRank = array();
foreach ($ranks->standings[0]->table as $rank) {
    $team = $rank->team;

    $position = $rank->position;
    $name = $team->shortName;
    $playedGames = $rank->playedGames;
    $won = $rank->won;
    $draw = $rank->draw;
    $lost = $rank->lost;
    $points = $rank->points;
    $goalsFor = $rank->goalsFor;
    $goalsAgainst = $rank->goalsAgainst;
    $goalDifference = $rank->goalDifference;

    $teamRank = new rank($position, $name, $playedGames, $won, $draw, $lost, $points, $goalsFor, $goalsAgainst, $goalDifference);
    $arrayRank[] = $teamRank;
}

$topPlayers = conexaoAPI("http://api.football-data.org/v4/competitions/EC/scorers?season={$year}", $chave);
$arrayTopPlayer = array();
if (empty($topPlayers->scorers)) {
} else {
    foreach ($topPlayers->scorers as $topPlayer) {
        $id = $topPlayer->player->id;
        $name = $topPlayer->player->name;
        $team = $topPlayer->team->name;
        $goals = $topPlayer->goals;
        $assists = $topPlayer->assists;
        if ($assists === null) {
            $assists = 0;
        }
        $penalties = $topPlayer->penalties;
        if ($penalties === null) {
            $penalties = 0;
        }
        $rankPlayer = new player($name, $team, $goals, $assists, $penalties);
        $arrayTopPlayer[] = $rankPlayer;
    }
}

$msgErroAPI = "Aguarde um momento, estamos processando sua solicitação. Parece que a resposta da API está demorando mais do que o esperado. 
Isso pode ser devido a um grande volume de dados ou a problemas temporários de conectividade. Por favor, seja paciente e, se o problema persistir, 
entre em contato conosco para que possamos investigar. Agradecemos sua compreensão e paciência";
?>
<main>
    <div class="col-left">
        <div class="calendar" id="calendar">
            <?php displayCalendar($month, $year); ?>
        </div>
        <div class="rank">
            <h1 class="title-rank">Classificação - European Championship</h1>
            <br>
            <?php if (empty($arrayRank)) { ?>
                <p class="msgError">
                    <?php echo $msgErroAPI ?>
                </p>
            <?php } else { ?>
                <table id="rank">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Clube</th>
                            <th>J</th>
                            <th class="remove">Vit</th>
                            <th class="remove">E</th>
                            <th class="remove">Der</th>
                            <th class="remove">GM</th>
                            <th class="remove">GC</th>
                            <th class="remove">SG</th>
                            <th>PTS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($arrayRank as $rank) { ?>
                            <tr>
                                <td class="number"><?php echo $rank->position ?></td>
                                <td><?php echo $rank->name ?></td>
                                <td class="number"><?php echo $rank->playedGames ?></td>
                                <td class="number remove"><?php echo $rank->won ?></td>
                                <td class="number remove"><?php echo $rank->draw ?></td>
                                <td class="number remove"><?php echo $rank->lost ?></td>
                                <td class="number remove"><?php echo $rank->goalsFor ?></td>
                                <td class="number remove"><?php echo $rank->goalsAgainst ?></td>
                                <td class="number remove"><?php echo $rank->goalDiference ?></td>
                                <td class="number"><?php echo $rank->points ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
    <div class="col-center">
        <div class="title-img">
            <img class="flag" src="https://crests.football-data.org/EUR.svg" alt="Europe">
            <div>
                <h1 class="title">Europa</h1>
                <h1 class="sub-title">European Championship</h1>
            </div>
        </div>
        <div class="main-header">
            <div class="btns">
                <div class="btn-inf">
                    <button id="btn-info">
                        <i class="bi bi-info-circle-fill"></i>
                    </button>
                </div>
                <div class="btn-calendar">
                    <button id="btn-calendar">
                        <i class="bi bi-calendar3"></i>
                    </button>
                </div>
            </div>
            <div class="info-and-cale">
                <div class="inf-score" id="inf-score">
                    <p>A API .football-data.org apresenta alguns erros nas datas dos jogos, o que compromete a precisão das informações a 100%. Além disso,
                        a primeira data que ela disponibiliza é 15/04/2023, pelo menos para os jogos do Brasileirão Série A. É importante mencionar que,
                        como estou utilizando a versão gratuita da API, mudanças futuras podem ocorrer com as datas dos jogos.
                    </p>
                </div>
                <div class="calendar" id="calendar">
                    <?php displayCalendar($month, $year); ?>
                </div>
            </div>
        </div>
        <div class="games">

            <?php if (empty($arrayGames)) { ?>
                <p id="msgError">
                    Desculpe, as informações dos jogos para <?php echo "$day/$month/$year"; ?> não estão disponíveis no momento.
                    Esta versão da API .football-data.org tem algumas restrições para usuários gratuitos, e
                    pode ser que os dados para o dia de hoje ainda não estejam disponíveis. Por favor,
                    tente novamente mais tarde ou considere verificar informações em outras fontes.
                    <br>
                    <br>
                    Agradecemos a sua compreensão.
                </p>
            <?php } else { ?>
                <?php foreach ($arrayGames as $game) { ?>
                    <div class="game">
                        <div class="data">
                            <h1><?php echo $game->date ?></h1>
                        </div>
                        <div class="teams">
                            <div class="team-one">
                                <div class="name-flag">
                                    <img src="<?php echo $game->flagTeamOne ?>" alt="<?php echo $game->nameTeamOne ?>">
                                    <h3 class="name-team-one"><?php echo $game->nameTeamOne ?></h3>
                                </div>
                                <h3 class="score-team-one"><?php echo $game->scoreTeamOne ?></h3>
                            </div>
                            <div class="team-two">
                                <div class="name-flag">
                                    <img src="<?php echo $game->flagTeamTwo ?>" alt="<?php echo $game->nameTeamTwo ?>">
                                    <h3 class="name-team-two"><?php echo $game->nameTeamTwo ?></h3>
                                </div>
                                <h3 class="score-team-two"><?php echo $game->scoreTeamTwo ?></h3>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <div class="col-rigth">
    <div class="placar">
            <?php
            $randomGame = array_rand($arrayGames);
            $game = $arrayGames[$randomGame];
            ?>
            <div class="placarTeam">
                <div class="placarTeamLogo">
                    <img src="<?php echo $game->flagTeamOne ?>" alt="">
                </div>
                <h5><?php echo $game->nameTeamOne ?></h5>
            </div>
            <h1>X</h1>
            <div class="placarTeam">
                <div class="placarTeamLogo">
                    <img src="<?php echo $game->flagTeamTwo ?>" alt="">
                </div>
                <h5><?php echo $game->nameTeamTwo ?></h5>
            </div>
        </div>
        <div class="top-players">
            <h1 class="title-top-player">Top 10 Players</h1>
            <br>
            <?php if (empty($arrayTopPlayer)) { ?>
                <p class="msgError">
                    <?php echo $msgErroAPI ?>
                </p>
            <?php } else { ?>
                <table class="top-players-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Clube</th>
                            <th>Gols</th>
                            <th class="remove">Assistências</th>
                            <th class="remove">Penalidades</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($arrayTopPlayer as $topPlayer) { ?>
                            <tr>
                                <td><?php echo $topPlayer->name ?></td>
                                <td><?php echo $topPlayer->team ?></td>
                                <td class="number"><?php echo $topPlayer->goals ?></td>
                                <td class="number remove"><?php echo $topPlayer->assists ?></td>
                                <td class="number remove"><?php echo $topPlayer->penalties ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
</main>
<?php

include('../futebol_score/assets/INCLUDE/footer.php');
