<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipe;
use App\Models\User;
use App\Models\Question;
use App\Models\ReponseUtilisateur;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $equipes = Equipe::all();
        $questions = Question::all();
        $selectedEquipeId = $request->get('equipe_id');
        $users = collect();
        $reponses = collect();

        $nbOui = 0;
        $nbNon = 0;
        if ($selectedEquipeId) {
            $users = User::where('equipe_id', $selectedEquipeId)->with('equipe')->get();
            $userIds = $users->pluck('id');
            $reponses = ReponseUtilisateur::whereIn('user_id', $userIds)->get();
            $nbOui = $reponses->where('reponse', 'oui')->count();
            $nbNon = $reponses->where('reponse', 'non')->count();
        }

        return view('admin.dashboard', compact('equipes', 'users', 'questions', 'reponses', 'selectedEquipeId', 'nbOui', 'nbNon'));
    }
}
