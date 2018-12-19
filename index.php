<?php
$lines = file(__DIR__ . '/input.txt', FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
$register_0 = 0;
$register_1 = 0;
$register_2 = 0;
$register_3 = 0;
$register = [0, 0, 0, 0];
function executeInstruction($instruction, $a, $b, $c, &$register)
{
    switch ($instruction) {
        case 'addr':
            $register[$c] = (int) $register[$a] + (int) $register[$b];
            break;
        case 'addi':
            $register[$c] = (int) $register[$a] + (int) $b;
            break;
        case 'mulr':
            $register[$c] = (int) $register[$a] * (int) $register[$b];
            break;
        case 'muli':
            $register[$c] = (int) $register[$a] * (int) $b;
            break;
        case 'banr':
            $register[$c] = (int) $register[$a] & (int) $register[$b];
            break;
        case 'bani':
            $register[$c] = (int) $register[$a] & (int) $b;
            break;
        case 'borr':
            $register[$c] = (int) $register[$a] | (int) $register[$b];
            break;
        case 'bori':
            $register[$c] = (int) $register[$a] | (int) $b;
            break;
        case 'setr':
            $register[$c] = (int) $register[$a];
            break;
        case 'seti':
            $register[$c] = (int) $a;
            break;
        case 'gtir':
            $register[$c] = (int) $a > (int) $register[$b] ? 1 : 0;
            break;
        case 'gtri':
            $register[$c] = (int) $register[$a] > (int) $b ? 1 : 0;
            break;
        case 'gtrr':
            $register[$c] = (int) $register[$a] > (int) $register[$b] ? 1 : 0;
            break;
        case 'eqir';
            $register[$c] = (int) $a === (int) $register[$b] ? 1 : 0;
            break;
        case 'eqri':
            $register[$c] = (int) $register[$a] === (int) $b ? 1 : 0;
            break;
        case 'eqrr':
            $register[$c] = (int) $register[$a] === (int) $register[$b] ? 1 : 0;
            break;
    }
}
function checkBehaviour($triplet)
{
    $opocodes = [];
    $before;
    $instruction = preg_split('/\s/', $triplet[1]);
    $after;
    for ($i = 0; $i < 4; $i++) {
        $before [$i] = $triplet[0][9 + 3 * $i];
        $after [$i] = $triplet[2][9 + 3 * $i];
    }
    $registerA = (int)$before[$instruction[1]];
    $registerB = (int)$before[$instruction[2]];
    $registerC = (int)$before[$instruction[3]];
    $resultC = (int)$after[$instruction[3]];
    $valueA = (int)$instruction[1];
    $valueB = (int)$instruction[2];
    $valueC = (int)$instruction[3];
    $registerAAfter = (int)$after[$instruction[1]];
    $registerBAfter = (int)$after[$instruction[2]];
    $registerCAfter = (int)$after[$instruction[3]];

    // ADDR
    if ( $registerA + $registerB === $resultC) {
        array_push($opocodes, 'addr');
    }
    // ADDI
    if ( $registerA + $valueB === $resultC) {
        array_push($opocodes, 'addi');
    }
    // MULR
    if ( $registerA * $registerB === $resultC) {
        array_push($opocodes, 'mulr');
    }
    // MULI
    if ( $registerA * $valueB === $resultC) {
        array_push($opocodes, 'muli');
    }
    // BANR
    if ( ($registerA & $registerB) === $resultC) {
        array_push($opocodes, 'banr');
    }
    // BANI
    if ( ($registerA & $valueB) === $resultC) {
        array_push($opocodes, 'bani');
    }
    // BORR
    if ( ($registerA | $registerB) === $resultC) {
        array_push($opocodes, 'borr');
    }
    // BORI
    if ( ($registerA | $valueB) === $resultC) {
        array_push($opocodes, 'bori');
    }
    // SETR
    if ( $registerA === $resultC) {
        array_push($opocodes, 'setr');
    }    
    // SETI
    if ( $valueA === $resultC) {
        array_push($opocodes, 'seti');
    }
    // GTIR
    if ($valueA > $registerB) {
        if ( $registerCAfter === 1) {
            array_push($opocodes, 'gtir');
        }  
    } else {
        if ( $registerCAfter === 0) {
            array_push($opocodes, 'gtir');
        }  
    }
    // GTRI
    if ($registerA > $valueB) {
        if ( $registerCAfter === 1) {
            array_push($opocodes, 'gtri');
        }  
    } else {
        if ( $registerCAfter === 0) {
            array_push($opocodes, 'gtri');
        }  
    }
    // GTRR
    if ($registerA > $registerB) {
        if ( $registerCAfter === 1) {
            array_push($opocodes, 'gtrr');
        }  
    } else {
        if ( $registerCAfter === 0) {
            array_push($opocodes, 'gtrr');
        }  
    }
    // EQIR
    if ($valueA === $registerB) {
        if ( $registerCAfter === 1) {
            array_push($opocodes, 'eqir');
        }        
    } else {
        if ( $registerCAfter === 0) {
            array_push($opocodes, 'eqir');
        }  
    }
    // EQRI
    if ($registerA === $valueB) {
        if ( $registerCAfter === 1) {
            array_push($opocodes, 'eqri');
        }    
    } else {
        if ( $registerCAfter === 0) {
            array_push($opocodes, 'eqri');
        }  
    }    
    // EQRR
    if ($registerA === $registerB) {
        if ( $registerCAfter === 1) {
            array_push($opocodes, 'eqrr');
        }    
    } else {
        if ( $registerCAfter === 0) {
            array_push($opocodes, 'eqrr');
        }    
    }    
    return $opocodes;
}
// The first part
$knownInstructions = [];
$count = 0;
$hugeOpocoeds = 0;
$triplet = [];
$records = [];
$j = 0;
foreach ($lines as $line) {
    $triplet[$count++] = $line;
    if ($count === 3) {
        $result = checkBehaviour($triplet);
        $opcodeNumber = explode(' ', $triplet[1]);
        $records[$j] = $result;
        $records[$j]['opcodeNumber'] = (int) $opcodeNumber[0];
        $j++;
        if( count($result) >= 3) {
            $hugeOpocoeds++;
        } else if ( count($result) === 1) {
            
            foreach ($result as $r) {
                $knownInstructions[$opcodeNumber[0]] = $r;
            }
        }
        $count = 0;
    }
}
error_reporting(E_ERROR);
while (count($knownInstructions) < 16) {
    for ($i = 0; $i < count($records); $i++) {
        if (is_array($records[$i])) {
            $opcodes = $records[$i];
            $opcodeNumber = $opcodes['opcodeNumber'];
            unset($opcodes['opcodeNumber']);
            $diff = array_diff($opcodes, $knownInstructions);
            if (empty($diff)) {
                unset($records[$i]);
            }
            if (count($diff) === 1) {
                foreach ($diff as $d) {
                    $knownInstructions[$opcodeNumber] = $d;
                }
            }
        }
    }
}
$instructionsLines = file(__DIR__ . '/instructions.txt', FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
$instructions = array_map(function($line) {
    return explode(' ', $line);
}, $instructionsLines);
for ($z = 0; $z < count($instructions); $z++) {
    $id = $knownInstructions[(int) $instructions[$z][0]];
    executeInstruction($id, $instructions[$z][1], $instructions[$z][2], $instructions[$z][3], $register);
}
var_dump($register);exit();