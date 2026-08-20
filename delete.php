<?php
 header("Access-Control-Allow-Origin: *"); // หรือระบุโดเมนของคุณเพื่อความปลอดภัย
  header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  // 2. จัดการกับ Preflight Request (OPTIONS)
  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
      http_response_code(200);
      exit();
  }

  header('Content-Type: application/json');

  $response = [];

  try {
      $json_data = file_get_contents('php://input');
      $data = json_decode($json_data, true);

      // *IMPORTANT*: Set your base upload directory (must match upload.php)
      $baseDir = __DIR__ . '/uploads/'; // ตรวจสอบให้แน่ใจว่า Path นี้ถูกต้องบน Server ของคุณ

      // 1. Action: Scan existing files in target folder
      if (isset($data['action']) && $data['action'] === 'scan_files' && !empty($data['path'])) {
          $subDir = trim($data['path'], '/');
          $targetDir = $baseDir . $subDir;
          $fileList = [];
          if (is_dir($targetDir)) {
              $scanned = scandir($targetDir);
              foreach ($scanned as $f) {
                  if ($f === '.' || $f === '..') continue;
                  if (is_file($targetDir . '/' . $f)) {
                      $fileList[] = $f;
                  }
              }
          }
          echo json_encode(['status' => 'success', 'files' => $fileList]);
          exit();
      }

      // 2. Action: Cleanup old temporary chunks
      if (isset($data['action']) && $data['action'] === 'clean_chunks') {
          // Cleanup old temporary chunks in chunks directory (older than 6 hours)
          $chunksDir = rtrim($baseDir, '/') . '/chunks';
          $cleaned = 0;
          if (is_dir($chunksDir)) {
              $files = scandir($chunksDir);
              $now = time();
              foreach ($files as $f) {
                  if ($f === '.' || $f === '..') continue;
                  $filePath = $chunksDir . '/' . $f;
                  // Delete chunk files older than 6 hours or force clean if requested
                  if (is_file($filePath) && ($now - filemtime($filePath) > 3600 * 6 || !empty($data['force']))) {
                      if (@unlink($filePath)) $cleaned++;
                  }
              }
          }
          echo json_encode(['status' => 'success', 'message' => "ล้างไฟล์ Chunk ชั่วคราวสำเร็จ ($cleaned ไฟล์)", 'cleaned' => $cleaned]);
          exit();
      }

      if (isset($data['files']) && is_array($data['files']) && isset($data['path'])) {
          $subDir = trim($data['path'], '/');
          $targetDir = $baseDir . $subDir;

          $deletedFiles = [];
          $failedFiles = [];

          foreach ($data['files'] as $filename) {
              // Security check to prevent directory traversal
              if (strpos($filename, '..') !== false || strpos($filename, '/') !== false) {
                  $failedFiles[] = [
                      'filename' => $filename,
                      'error' => 'Security check failed: Invalid filename or path traversal attempt.'
                  ];
                  continue;
              }

              $filePath = $targetDir . '/' . $filename;

              if (file_exists($filePath)) {
                  if (unlink($filePath)) {
                      $deletedFiles[] = $filename;
                  } else {
                      // Capture PHP's last error message for more details
                      $lastError = error_get_last();
                      $failedFiles[] = [
                          'filename' => $filename,
                          'error' => 'Failed to unlink file (permissions issue?). PHP Error: ' . ($lastError['message'] ?? 'Unknown error.')
                      ];
                  }
              } else {
                  $failedFiles[] = [
                      'filename' => $filename,
                      'error' => 'File not found on server at ' . $filePath
                  ];
              }
          }

          // Determine overall status based on deletion results
          if (empty($failedFiles) && !empty($deletedFiles)) {
              $status = 'success'; // ลบสำเร็จทั้งหมด
              $message = 'All files deleted successfully.';
          } elseif (!empty($failedFiles) && !empty($deletedFiles)) {
              $status = 'partial_success'; // ลบได้บางส่วน
              $message = 'Some files could not be deleted.';
          } else {
              $status = 'error'; // ล้มเหลวทั้งหมด หรือไม่มีไฟล์ให้ลบ
              $message = 'No files were deleted or specified.';
          }

          $response = [
              'status' => $status,
              'deleted' => $deletedFiles,
              'failed' => $failedFiles,
              'message' => $message
          ];
          http_response_code(200);

      } else {
          throw new Exception('Invalid request data. Expected \'files\' (array) and \'path\' (string).');
      }

  } catch (Exception $e) {
      $response = [
          'status' => 'error',
          'message' => $e->getMessage()
      ];
      http_response_code(400);
  }

  echo json_encode($response);
