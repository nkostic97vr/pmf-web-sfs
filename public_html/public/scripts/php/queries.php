<?php

    function qGetTopicsByForumId($id, $sort = ["started" => "ASC"]) {
        $id = dbEscape($id);
        
        $sql = "SELECT * ";
        $sql .= "FROM topics ";
        $sql .= "WHERE forums_id='{$id}' ";
        $sql .= "ORDER BY ";
        $counter = count($sort);
        foreach($sort as $column => $direction) {
            $sql .= "{$column} {$direction}";
            $sql .= (--$counter > 0) ? ", " : " ";
        }
        
        return execAndFetchAssoc($sql, FETCH::ALL);
    }
    
    function qCountPostsInTopic($topicId) {
        $topicId = dbEscape($topicId);
        
        $sql = "SELECT COUNT(*) AS count ";
        $sql .= "FROM posts ";
        $sql .= "WHERE topics_id='{$topicId}' ";
        
        return execAndFetchAssoc($sql)["count"];
    }
    
    function qCountTopicsInForum($forumId) {
        $forumId = dbEscape($forumId);
        
        $sql = "SELECT COUNT(*) AS count ";
        $sql .= "FROM topics ";
        $sql .= "WHERE forums_id='{$forumId}' ";
        
        return execAndFetchAssoc($sql)["count"];
    }
    
    function qCountPostsInForum($forumId) {
        $count = 0;
        $topics = qGetTopicsByForumId($forumId);
        foreach ($topics as $topic) {
            $count += qCountPostsInTopic($topic["id"]);
        }
        return $count;
    }
    
    function qCountTopicsInRootForum($forumId) {
        $count = qCountTopicsInForum($forumId);
        $childForums = qGetForumsByParentId($forumId);
        foreach ($childForums as $childForum) {
            $count += qCountTopicsInForum($childForum["id"]);
        }
        return $count;
    }
    
    function qCountPostsInRootForum($forumId) {
        $count = qCountPostsInForum($forumId);
        $childForums = qGetForumsByParentId($forumId);
        foreach ($childForums as $childForum) {
            $count += qCountPostsInForum($childForum["id"]);
        }
        return $count;
    }
    
    function qGetTopicStarterUsername($firstPostId) {
        $firstPostId = dbEscape($firstPostId);
        
        $sql = "SELECT users_id ";
        $sql .= "FROM posts ";
        $sql .= "WHERE id='{$firstPostId}' ";
        
        $userId = execAndFetchAssoc($sql)["users_id"];
        
        $sql = "SELECT username ";
        $sql .= "FROM users ";
        $sql .= "WHERE id='{$userId}' ";
        
        return execAndFetchAssoc($sql)["username"];
    }
    
    function qGetTopicLastPosterUsername($topicId) {
        $topicId = dbEscape($topicId);
        
        $sql = "SELECT users_id ";
        $sql .= "FROM posts ";
        $sql .= "WHERE topics_id='{$topicId}' ";
        $sql .= "AND posted=( ";
        $sql .= "   SELECT MAX(posted) ";
        $sql .= "   FROM posts ";
        $sql .= "   WHERE topics_id='{$topicId}' ";
        $sql .= ") ";
        
        $userId = execAndFetchAssoc($sql)["users_id"];
        
        $sql = "SELECT username ";
        $sql .= "FROM users ";
        $sql .= "WHERE id='{$userId}' ";
        
        return execAndFetchAssoc($sql)["username"];
    }