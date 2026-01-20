<?php

define('APP_INIT', true);
// Répertoire de destination
$uploadDir = 'uploads/fatca/';
if (!is_dir($uploadDir)) {
  mkdir($uploadDir, 0755, true);
}

// Vérification du fichier
if (!empty($_FILES) && isset($_FILES['FATCA_fichier'])) {
  $file = $_FILES['FATCA_fichier'];
  $originalName = basename($file['name']);
  $extension = pathinfo($originalName, PATHINFO_EXTENSION);

  // Génération nom unique
  $filename = uniqid('fatca_') . '.' . $extension;
  $targetPath = $uploadDir . $filename;

  if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    echo json_encode([
      "status" => "success",
      "path" => $targetPath // ou basename si tu préfères un chemin relatif
    ]);
  } else {
    http_response_code(500);
    echo json_encode([
      "status" => "error",
      "message" => "Erreur lors du déplacement du fichier."
    ]);
  }
} else {
  http_response_code(400);
  echo json_encode([
    "status" => "error",
    "message" => "Aucun fichier n'a été reçu."
  ]);
}
