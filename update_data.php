<?php
// update_data.php

header('Content-Type: application/json');
require_once 'vendor/autoload.php';
require_once 'models/question.php';


$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'];

try {
    $record = Question::find($id);
    if($record->problem != $data['problem']){
      $record->problem = $data['problem'];
      $record->problem_updated = 1;
    }
    if($record->origin != $data['origin']){
      $record->origin = $data['origin'];
      $record->origin_updated = 1;
    }
    if($record->spell != $data['spell']){
      $record->spell = $data['spell'];
      $record->spell_updated = 1;
    }
    if($record->chinese_meaning != $data['chinese_meaning']){
      $record->chinese_meaning = $data['chinese_meaning'];
      $record->chinese_meaning_updated = 1;
    }
    $record->save();

    echo json_encode(['success' => true]);
} catch (\ActiveRecord\RecordNotFound $e) {
    echo json_encode(['error' => 'Record not found']);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

