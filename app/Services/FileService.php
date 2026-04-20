<?php 
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileService {


   /**
    * upload multi files and attach with model
    */
    public function uploadMultiple(Model $model, array $files, string $collection = 'default'): void
    {
        foreach ($files as $file) {
            $this->upload($model, $file, $collection);
        }
    }

  /**
   * upload one file
   */
    public function upload(Model $model, UploadedFile $file, string $collection = 'default')
    {
        $folder = strtolower(class_basename($model));
        $path = $file->store("attachments/{$folder}");

        return $model->attachments()->create([
            'file_path'       => $path,
            'file_name'       => $file->getClientOriginalName(),
            'file_type'       => $file->getClientMimeType(),
            'file_size'       => $file->getSize(),
            'collection_name' => $collection,
        ]);
    }

  /**
   * delete from db too
   */
    public function delete($attachment): bool
    {
        if (Storage::exists($attachment->file_path)) {
            Storage::delete($attachment->file_path);
        }
        return $attachment->delete();
    }
}
