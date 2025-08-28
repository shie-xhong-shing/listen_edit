<?php

header('Content-Type: application/json');
require_once 'vendor/autoload.php';
require_once 'models/question.php';

$id = $_GET['id'];
try {
    $record = Question::find($id);
    echo json_encode($record->attributes());
} catch (\ActiveRecord\RecordNotFound $e) {
    echo json_encode(['error' => 'Record not found']);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

