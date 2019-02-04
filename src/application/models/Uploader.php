<?php
class Model_Uploader {

    const FILE_UPLOAD_DIR = 'files';

    private $_filesModel;
    private $_fileRelationsModel;
    private $_translate;

    public function __construct($translate = null) {
        $this->_filesModel = new Model_DbTable_Files();
        $this->_fileRelationsModel = new Model_DbTable_FileRelations();
        $this->_translate = $translate;
    }

    public function getFiles($module_id, $action_id, $post_id) {
        return $this->_fileRelationsModel->getFiles($module_id, $action_id, $post_id);
    }

    /**
     * @param $file
     * @param $module_id
     * @param $action_id
     * @param $post_id
     * @return bool
     */
    public function upload($file, $module_id, $action_id, $post_id) {
        if ($file[key($file)]['error'] != 0)
            return false;

        $file_name = $file[key($file)]['name'];
        $tmp_file = $file[key($file)]['tmp_name'];

        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $hash = md5_file($tmp_file);
        $db_info = $this->_filesModel->checkHash($hash);


        if (!$db_info) {
            $path = $this->uploadFile($tmp_file, $ext, $hash);
            $file_id = $this->insertFileInDb($file_name, $hash, $ext, $path);
        } else {
            $file_id = $db_info['id'];
        }

        return $this->createRelation($file_id, $file_name, $module_id, $action_id, $post_id);
    }

    /**
     * @param $relation_id
     * @return bool
     */
    public function deleteRelation($relation_id) {
        if (!$relation_id || !is_numeric($relation_id))
            return false;

        $info = $this->_fileRelationsModel->countFileLinks($relation_id);

        $count = $info['count'];
        $file_id = $info['file_id'];

        if (!$file_id)
            return false;

        if ($count <= 1) {
            try {
                $file = $this->_filesModel->find($file_id)->current();

                if (!$file)
                    return false;

                $path = $file['path'];
                $file->delete();
                @unlink($path);
            } catch (Exception $e) {
                echo $e;
                return false;
            }
        }

        try {
            $relation = $this->_fileRelationsModel->find($relation_id)->current();

            if (!$relation)
                return false;

            $relation->delete();
            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function deleteRelationsByMAP($module_id, $action_id, array $post_ids) {
        if (!$module_id || !is_numeric($module_id) || !$action_id || !is_numeric($action_id) || !$post_ids)
            return false;

        $fileStatuses = $this->_fileRelationsModel->getFileStatusToDeleteByMAP($module_id, $action_id, $post_ids);

        if (!$fileStatuses)
            return false;

        $toDeleteList = array();

        foreach ($fileStatuses as $status)
            if ($status['to_delete'] == '1')
                array_push($toDeleteList, $status['file_id']);

        $fileInfo = $this->_filesModel->getFileInfoByIds($toDeleteList);
        $this->_filesModel->deleteFilesByInfo($fileInfo);

        foreach ($fileInfo as $file)
            @unlink($file['path']);

        $this->_fileRelationsModel->deleteRelationsByMAP($module_id, $action_id, $post_ids);
    }

    /**
     * WARNING!
     * Very dangerous function, which delete ALL RELATIONS CONNECTED WITH THIS FILE.
     * @param $file_id
     * @return bool
     */
    public function forceFileDelete($file_id) {
        if (!$file_id || !is_numeric($file_id))
            return false;

        try {
            $file = $this->_filesModel->find($file_id)->current();

            if (!$file)
                return false;

            $this->_fileRelationsModel->delete('file_id = ' . $this->_fileRelationsModel->getAdapter()->quote($file_id));
            $path = $file['path'];
            $file->delete();
            @unlink($path);
        } catch (Exception $e) {
            echo $e;
        }
    }

    /**
     * @param $tmp_file
     * @param $ext
     * @param $hash
     * @return string
     */
    private function uploadFile($tmp_file, $ext, $hash) {
        if (!move_uploaded_file($tmp_file, $link = self::FILE_UPLOAD_DIR . DIRECTORY_SEPARATOR . $hash . '.' . $ext)) {
            echo 'error while uploading';
        }

        return $link;
    }

    /**
     * @param $title
     * @param $hash
     * @param $ext
     * @param $path
     * @return mixed|null
     */
    private function insertFileInDb($title, $hash, $ext, $path) {
        try {
            $new_db_file = $this->_filesModel->createRow();
            $new_db_file->setFromArray(array(
                FilesFields::TITLE => $title,
                FilesFields::HASH => $hash,
                FilesFields::EXT => $ext,
                FilesFields::PATH => $path,
                FilesFields::DATE => date('Y-m-d')
            ));
            return $new_db_file->save();
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param $file_id
     * @param $module_id
     * @param $action_id
     * @param $post_id
     * @return mixed|null
     */
    private function createRelation($file_id, $title, $module_id, $action_id, $post_id) {
        try {
            $new_file_relation = $this->_fileRelationsModel->createRow();
            $new_file_relation->setFromArray(array(
                FileRelationsFields::FILE_ID => $file_id,
                FileRelationsFields::TITLE => $title,
                FileRelationsFields::MODULE_ID => $module_id,
                FileRelationsFields::ACTION_ID => $action_id,
                FileRelationsFields::POST_ID => $post_id
            ));
            return $new_file_relation->save();
        } catch (Exception $e) {
            return false;
        }
    }
}