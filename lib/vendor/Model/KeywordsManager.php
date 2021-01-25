<?php


namespace Model;


use Entity\KeywordEntity;
use RCFramework\Manager;

class KeywordsManager extends Manager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getOneKeyword($keywordId)
    {
        $answerKeywordData = $this->db->prepare('SELECT keywords_id, keywords_keyword FROM rc_photographe_keywords WHERE keywords_id=:keywordId');
        $answerKeywordData->execute(array(
            'keywordId' => $keywordId
        ));

        $dbKeyword = $answerKeywordData->fetch();

        $keywordEntity = new KeywordEntity($dbKeyword);

        return $keywordEntity;
    }

    public function saveOneKeyword(KeywordEntity $newKeywordEntity)
    {
        $req = $this->db->prepare('INSERT INTO rc_photographe_keywords(keywords_keyword) VALUES(:keywords_keyword)');
        $req->execute(array(
            'keywords_keyword' => $newKeywordEntity->keyword()
        ));
    }

    public function deleteOneKeyword($keywordId)
    {
        $req = $this->db->prepare('DELETE FROM rc_photographe_keywords WHERE keywords_id=:keywordId');
        $req->execute(array(
            'keywordId' => $keywordId
        ));
    }
}
