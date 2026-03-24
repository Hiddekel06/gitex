<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipe;
use App\Models\User;
use App\Models\Question;
use App\Models\ReponseUtilisateur;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $equipes = Equipe::all();
        $users = User::with('equipe')->get();
        $questions = Question::all();
        $reponses = ReponseUtilisateur::all();

        return view('admin.dashboard', compact('equipes', 'users', 'questions', 'reponses'));
    }
}
