<?php
if (!is_dir('in')) {
    echo "Please provide create an 'in' directory and put your FactionsPro.db inside.\n";
    exit(1);
}
if (!is_file('in/FactionsPro.db')) {
    echo "Please put your FactionsPro.db inside the 'in' directory.\n";
    exit(1);
}
$factionsDB = new \SQLite3('in/FactionsPro.db');
$playersDB = [];
$namesDB = [];
$clansDBs = [];

convertClansBase($factionsDB, $clansDBs);
convertPlayersBase($factionsDB, $playersDB, $namesDB, $clansDBs);
convertHomes($factionsDB, $clansDBs);

if (!is_dir('out')) mkdir('out');
if (!is_dir('out/clans')) mkdir('out/clans');
file_put_contents('out/players.json', json_encode($playersDB, JSON_PRETTY_PRINT));
file_put_contents('out/names.json', json_encode($namesDB, JSON_PRETTY_PRINT));
foreach ($clansDBs as $clanDB) {
    file_put_contents('out/clans/' . $clanDB['name'] . '.json', json_encode($clanDB, JSON_PRETTY_PRINT));
}

function convertClansBase(SQLite3 $factionsDB, array &$clansDBs)
{
    $stmt = $factionsDB->prepare('SELECT faction FROM strength');
    $result = $stmt->execute();
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $clansDBs[$row['faction']] = [
            'name' => $row['faction'],
            'members' => [],
            'leader' => '',
            'color' => 'f',
            'bank' => 0
        ];
    }
}

function convertPlayersBase(SQLite3 $factionsDB, array &$playersDB, array &$namesDB, array &$clansDBs)
{
    $stmt = $factionsDB->prepare('SELECT * FROM master');
    $result = $stmt->execute();
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $namesDB[strtolower($row['player'])] = $row['player'];
        $playersDB[$row['player']] = $row['faction'];
        switch ($row['rank']) {
            case 'Leader':
                $rank = 'leader';
                break;
            case 'Officer':
                $rank = 'vim';
                break;
            default:
                $rank = 'member';
                break;
        }
        $clansDBs[$row['faction']]['members'][strtolower($row['player'])] = $rank;
        if ($row['rank'] === 'Leader') {
            $clansDBs[$row['faction']]['leader'] = $row['player'];
        }
    }
}

function convertHomes(SQLite3 $factionsDB, array &$clansDBs)
{
    $stmt = $factionsDB->prepare('SELECT * FROM home');
    $result = $stmt->execute();
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $clansDBs[$row['faction']]['home'] = ['x' => $row['x'], 'y' => $row['y'], 'z' => $row['z'], 'world' => $row['world'], 'yaw' => 0, 'pitch' => 0];
    }
}