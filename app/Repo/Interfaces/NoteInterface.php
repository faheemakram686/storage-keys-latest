<?php
namespace App\Repo\Interfaces;

interface NoteInterface{
    public function saveNote($request);
    public function getAllNote();
    public function deleteNote($id);
    public function editNote($id);
    public function updateNote($request);
    public function getNote($request);
    public function getRelatedNote($request);

}
