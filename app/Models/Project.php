<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'status',
        'visibility'
    ];

    /**
     * 
     * Validate if you can edit, delete, add users, etc. 
     * The response depends on the subquerys canEdit, canDelete and canAddUsers
     * @param Project $project project to be managed
     * @return bool            true if you can managed the project, false if you can't
     * 
     */
    public static function canApplyActions(Project $project): bool
    {
        return self::canEdit($project) && self::canDelete($project) && self::canAddUsers($project);
    }

    /**
     * 
     * Validate if you can add users to project.
     * The response only will be true if you are the creator of the project
     * @param Project $project project to add users
     * @return bool            true if you can add users to the project, false if you can't
     * 
     */
    public static function canAddUsers(Project $project): bool
    {
        return self::isMyProject($project);
    }

    /**
     * 
     * Validate if you can edit project. 
     * The response only will be true if you are the creator of the project
     * @param Project $project project to be edited
     * @return bool            true if you can edit the project, false if you can't
     * 
     */
    public static function canEdit(Project $project): bool
    {
        return self::isMyProject($project);
    }

    /**
     * 
     * Validate if you can edit project. 
     * The response only will be true if you are the creator of the project
     * @param Project $project project to be deleted
     * @return bool            true if you can delete the project, false if you can't
     * 
     */
    public static function canDelete(Project $project): bool
    {
        return self::isMyProject($project);
    }

    /**
     * 
     * Validate if you are the creator of the project
     * @param Project $project project to be validated
     * @return bool            true if you are the creator of the project, false if you aren't
     * 
     */
    public static function isMyProject(Project $project): bool 
    {
        $user = auth()->user();
        $project = $user->projectCreated->find($project->id);
        return $project ? true : false;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
