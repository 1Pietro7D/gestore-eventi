<?php
class BaseController {
   protected function uploadMedia(array $file,  string $subdirectory, ?string $oldPath = null): ?string {
        $baseDir = __DIR__ . '/../public';
        $uploadDir = $baseDir . '/' . trim($subdirectory, '/');

        // Elimina file vecchio se presente
        $this->deleteMedia($oldPath);

        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
            return null;
        }
    
        
    
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
    
        $filename = uniqid() . '_' . basename($file['name']);
        $targetPath = $uploadDir . '/' . $filename;
    
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return '/' . trim($subdirectory, '/') . '/' . $filename;
        }
    
        return null;
    }
    protected function deleteMedia($path){
        $baseDir = __DIR__ . '/../public';
        if ($path) {
            $oldFullPath = $baseDir . $path;
            if (file_exists($oldFullPath)) {
                unlink($oldFullPath);
            }
        }
    }
}
