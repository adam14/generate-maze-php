<?php
if(isset($_GET['width'], $_GET['width'])){
    $maze_width = $_GET['width'];
    $maze_height = $_GET['height'];
} else{
    $maze_width = 15;
    $maze_height = 15;
}
$types_of_squares = 1;
$types_of_walls = 1;

$maze = [];
for($x = 0; $x < $maze_width; $x++){
    for($y = 0; $y < $maze_height; $y++) {
        $maze[$x][$y] = [
            'square_type' => rand(0, $types_of_squares - 1), 
            'walls_types'=>[
                'up' => rand(1, $types_of_walls),
                'right' => rand(1, $types_of_walls),
                'down' => rand(1, $types_of_walls),
                'left' => rand(1, $types_of_walls)
            ],
            'visited' => false
        ];
    };
}

$x_history = [];
$y_history = [];
$x_next = -1;
$y_next = -1;
$x = rand(0,$maze_width - 1);
$y = rand(0,$maze_height - 1);
$maze_size = $maze_width * $maze_height;

$visited_rows = 0;
$backtrack_steps = 0;
while($visited_rows < $maze_size){
    $available_directions = [];
    if($y - 1 >= 0 && !$maze[$x][$y - 1]['visited']) array_push($available_directions, 'up');
    if($x + 1  < $maze_width && !$maze[$x + 1][$y]['visited']) array_push($available_directions, 'right');
    if($y + 1  < $maze_height && !$maze[$x][$y + 1]['visited']) array_push($available_directions, 'down');
    if($x - 1 >= 0 && !$maze[$x - 1][$y]['visited']) array_push($available_directions, 'left');

    $count_available_directions = count($available_directions);
    if($maze[$x][$y]['visited'] === false) $visited_rows++;
    $maze[$x][$y]['visited'] = true;
    if($count_available_directions === 0){
        $x = $x_history[count($x_history) - 1 - $backtrack_steps];
        $y = $y_history[count($y_history) - 1 - $backtrack_steps];
        $backtrack_steps ++;
        continue;
    } else $backtrack_steps = 0;
    
    $decision = $available_directions[rand(0, count($available_directions) - 1)];
    if($decision === 'up'){
        $x_next = $x;
        $y_next = $y - 1;
        $maze[$x][$y]['walls_types']['up'] = 0;
        $maze[$x_next][$y_next]['walls_types']['down'] = 0;
    }
    elseif($decision === 'right'){
        $x_next = $x + 1;
        $y_next = $y;
        $maze[$x][$y]['walls_types']['right'] = 0;
        $maze[$x_next][$y_next]['walls_types']['left'] = 0;
    }
    elseif($decision === 'down'){
        $x_next = $x;
        $y_next = $y + 1;
        $maze[$x][$y]['walls_types']['down'] = 0;
        $maze[ $x_next][$y_next]['walls_types']['up'] = 0;
    }
    elseif($decision === 'left'){
        $x_next = $x - 1;
        $y_next = $y;
        $maze[$x][$y]['walls_types']['left'] = 0;
        $maze[$x_next][$y_next]['walls_types']['right'] = 0;
    }
    array_push($x_history,$x);
    array_push($y_history,$y);
    $x = $x_next;
    $y = $y_next;
}
$squares = [];
for($x = 0;$x < $maze_width; $x++){
    for($y = 0;$y < $maze_height; $y++){
        array_push($squares, ['type' =>$maze[$x][$y]['square_type'], 'walls' => [
            $maze[$x][$y]['walls_types']['up'],
            $maze[$x][$y]['walls_types']['right'],
            $maze[$x][$y]['walls_types']['down'],
            $maze[$x][$y]['walls_types']['left']
        ]]);
    }
}
?>
<html lang="en">
    <head>
        <title>PHP Maze Generator</title>
        <style>
            table{
                border-collapse: collapse;
                
            }
            table, th, td{
                border:2px solid;
            }
            th, td{
               padding:5px
            }
            body{
                display:flex;
                align-items:center;
                flex-direction:column;
            }
        </style>
    </head>
    <body>
        <form>
            <label>Width:</label>
            <input name="width" type="number" value="<?php echo $maze_width ?>">
            <label>Height:</label>
            <input name="height" type="number" value="<?php echo $maze_height ?>"><br>
            <div style="text-align:center;margin-top:10px">
                <button>Generate maze</button>
            </div>
        </form>
        <table>
            <?php for($y = 0; $y<$maze_height; $y++): ?>
                <tr>
                    <?php for($x = 0; $x<$maze_width; $x++):
                        $row = &$maze[$x][$y]['walls_types'];
                        $wall_string = '';
                        for($z = 0; $z < 4; $z++){
                            if(array_values($row)[$z] != 0) $wall_string .= 'solid ';
                            else $wall_string .= 'none ';
                        }
                    ?>
                    <td style="border-style: <?php echo $wall_string ?>;"></td>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </table>
    </body>
</html>