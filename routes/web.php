<?php

use App\Models\Noticia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {

    $noticias = Noticia::orderBy('id', 'desc')->paginate(6);
    return view('home', compact('noticias'));
})->name('home');

Route::view('/teste', 'tela-teste');

Route::view('/cadastro', 'tela-cadastro')->name('telaCadastro');
Route::view('/login', 'login')->name('login');
Route::post(
    '/salva-usuario',
    function (Request $request) {
        $user = new User();
        $user->name = $request->nome;
        $user->email = $request->email;
        $user->password = $request->senha;
        $user->save();
        return redirect()->route('home');
    }
)->name('SalvarUsuario');

Route::post(
    '/logar',
    function (Request $request) {

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Usuário e senha inválidos.',
        ])->onlyInput('email');
    }
)->name('logar');

Route::get(
    '/logout',
    function (Request $request) {
        Auth::logout();
        $request->session()->regenerate();
        return redirect()->route('home');
    }
)->name('logout');


Route::get('/gerencia-noticias',
    function () {
        $noticias = Noticia::orderBy('id', 'desc')->paginate(6);
        return view('gerencia-noticias', compact('noticias'));
    }
)->name('gerenciaNoticias')->middleware('auth');


Route::get(
    '/cadastra-noticia',
    function () {
        $noticia = new Noticia();
        return view('cadastra-noticia', compact('noticia'));
    }
)->name('cadastraNoticia')->middleware('auth');

Route::post(
    '/salva-noticia',
    function (Request $request) {
        //  dd($request);

        $noticia = new Noticia();
        $noticia->titulo = $request->titulo;
        $noticia->resumo = $request->resumo;
        $noticia->capa   = $request->capa;
        $noticia->conteudo = $request->conteudo;

        $noticia->data = now();
        $noticia->user_id = Auth::id();
        $noticia->save();

        // $user = new User();
        // $user->name = $request->nome;
        //$user->email = $request->email;
        //$user->password = $request->senha;
        // $user->save();


        return redirect()->route('gerenciaNoticias');
    }
)->name('SalvaNoticia')->middleware('auth');

Route::get(
    '/exibe-noticia/{noticia}',
    function (Noticia $noticia) {

        // $noticia = Noticia::find($noticia);

        return view('exibe-noticia', compact('noticia'));
    }
)->name('exibeNoticia');

Route::get(
    '/edita-noticia/{noticia}',
    function (Noticia $noticia) {

        // $noticia = Noticia::find($noticia);

        return view('edita-noticia', compact('noticia'));
    }
)->name('editaNoticia')->middleware('auth');


Route::post(
    '/altera-noticia/{noticia}',
    function (Request $request, Noticia $noticia) {
        //  dd($request);


        $noticia->titulo = $request->titulo;
        $noticia->resumo = $request->resumo;
        $noticia->capa   = $request->capa;
        $noticia->conteudo = $request->conteudo;

        $noticia->data = now();
        $noticia->user_id = Auth::id();
        $noticia->save();



        return redirect()->route('gerenciaNoticias');
    }
)->name('alteraNoticia')->middleware('auth');

Route::get(
    '/deleta-noticia/{noticia}',
    function (Noticia $noticia) {

        $noticia->delete();
        return redirect()->route('gerenciaNoticias');
    }
)->name('deletaNoticia')->middleware('auth');

Route::view('/noticias', 'noticia-pagina')->name('NoticiaPagina');

Route::view('/alunosucesso', 'alunos-sucesso')->name('AlunosSucesso');

Route::view('/institucional', 'institucional')->name('Institucional');

Route::view('/sobrecurso', 'sobre-curso')->name('SobreCurso');

Route::view('/contato', 'contato')->name('contato');