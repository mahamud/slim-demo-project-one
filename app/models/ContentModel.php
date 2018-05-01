<?php

namespace App\models;

use App\exception\RecordNotFoundException;


/**
 * Class ContentModel
 * @package App\models
 */
class ContentModel extends BaseModel{

    /**
     * @return array
     * @throws RecordNotFoundException
     * @throws \Exception
     */
    public function getPosts(){
        try {
            $query = $this->db->table(TBL_CONTENT);
            $query = $query->skip($this->offset)->take($this->limit);
            $posts = $query->get();
            if (!empty($posts) && is_array($posts) && sizeof($posts) > 0) {
                return $this->result($posts);
            }
            throw new RecordNotFoundException(RECORD_NOT_FOUND);
        }
        catch(RecordNotFoundException $exception){
            throw $exception;
        }
        catch(\Exception $exception){
            $this->logException($exception);
            throw new \Exception(UNEXPECTED_DB_ERROR);
        }
    }


    /**
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function getPostDetail($id){
        try {
            $query = $this->db->table(TBL_CONTENT);
            $query = $query->where(TBL_CONTENT . '.id', '=', $id);
            $post = $query->first();
            if (!empty($post) && is_array($post)) {
                return $this->result($post);
            }
        }
        catch(\Exception $exception){
            $this->logException($exception);
            throw new \Exception(UNEXPECTED_DB_ERROR);
        }
        throw new \Exception(RECORD_NOT_FOUND);
    }

}